<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Business;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Models\ModerationWarning;
use App\Notifications\NewReportNotification;
use App\Notifications\ModerationWarningNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:post,service,business,user',
            'id' => 'required|integer',
            'reason' => 'required|string|max:1000',
        ]);

        $models = ['post' => Post::class, 'service' => Service::class, 'business' => Business::class, 'user' => User::class];
        $model = $models[$data['type']]::findOrFail($data['id']);
        $report = Report::create([
            'user_id' => auth('users')->id(),
            'reason' => $data['reason'],
            'post_id' => $data['type'] === 'post' ? $model->id : null,
            'reportable_type' => $models[$data['type']],
            'reportable_id' => $model->id,
        ]);

        $reporter = auth('users')->user();
        $target = match ($data['type']) {
            'post' => 'منشور',
            'service' => 'خدمة',
            'business' => 'حساب أعمال',
            default => 'حساب مستخدم'
        };
        $message = "بلاغ جديد من {$reporter->name} على {$target} رقم {$model->id}";
        Admin::all()->each(fn($admin) => $admin->notify(new NewReportNotification($message)));
        SuperAdmin::all()->each(fn($admin) => $admin->notify(new NewReportNotification($message)));

        return response()->json(['id' => $report->id, 'message' => 'تم إرسال البلاغ إلى الإدارة.'], 201);
    }

    public function index()
    {
        $reports = Report::with(['user', 'reportable'])->latest()->get();
        return Inertia::render('Admin/Reports', ['reports' => $reports]);
    }

    public function reportpost(int $id)
    {
        $reports = Report::with(['user', 'reportable'])->where('post_id', $id)->get();
        return Inertia::render('Admin/Reports', ['reports' => $reports]);
    }

    public function details(int $id)
    {
        $report = Report::with('user')->findOrFail($id);
        $target = $report->reportable;

        if (!$target && $report->post_id) {
            $target = Post::findOrFail($report->post_id);
        }

        if ($target instanceof Post) {
            $target->load(['user.businesses', 'activeType', 'comments.user', 'comments.replies.user', 'likes']);
            $type = 'post';
        } elseif ($target instanceof Service) {
            $target->load(['user.businesses', 'business', 'category', 'subcategory', 'city']);
            $type = 'service';
        } elseif ($target instanceof Business) {
            $target->load(['user', 'gallery']);
            $type = 'business';
        } elseif ($target instanceof User) {
            $target->load(['businesses.gallery', 'services.category', 'services.city', 'posts']);
            $type = 'user';
        } else {
            abort(404);
        }

        $owner = $target instanceof User ? $target : $target->user;
        $warnings = $owner
            ? ModerationWarning::where('user_id', $owner->id)->latest()->limit(50)->get()
            : collect();

        $page = request()->routeIs('super_admin.reports.details')
            ? 'SuperAdmin/ReportDetails'
            : 'Admin/ReportDetails';

        return Inertia::render($page, [
            'report' => $report,
            'target' => $target,
            'targetType' => $type,
            'warnings' => $warnings,
        ]);
    }

    public function warn(Request $request, int $id)
    {
        $data = $request->validate(['message' => 'required|string|max:2000']);
        $report = Report::findOrFail($id);
        $target = $report->reportable ?: ($report->post_id ? Post::findOrFail($report->post_id) : null);
        $owner = $target instanceof User ? $target : $target?->user;
        if (!$owner) return response()->json(['message' => 'لا يوجد مستخدم مرتبط بهذا البلاغ.'], 422);

        $warning = ModerationWarning::create($this->warningData($owner, $target, $data['message']));
        $owner->notify(new ModerationWarningNotification($warning->message, $warning->id, class_basename($target), $target->id));
        return response()->json(['message' => 'تم إرسال التحذير إلى إشعارات المستخدم.']);
    }

    public function warnTarget(Request $request, string $type, int $id)
    {
        $data = $request->validate(['message' => 'required|string|max:2000']);
        $models = ['post' => Post::class, 'service' => Service::class, 'business' => Business::class, 'user' => User::class];
        abort_unless(isset($models[$type]), 404);
        $target = $models[$type]::findOrFail($id);
        $owner = $target instanceof User ? $target : $target->user;
        abort_unless($owner, 422);
        $warning = ModerationWarning::create($this->warningData($owner, $target, $data['message']));
        $owner->notify(new ModerationWarningNotification($warning->message, $warning->id, class_basename($target), $target->id));
        return response()->json(['message' => 'تم إرسال التحذير إلى إشعارات المستخدم.']);
    }

    private function warningData(User $owner, object $target, string $message): array
    {
        $issuer = auth('super_admins')->user() ?: auth('admins')->user();
        $label = match (class_basename($target)) {
            'Post' => 'المنشور',
            'Service' => 'الخدمة',
            'Business' => 'حساب الأعمال',
            default => 'الحساب',
        };
        $name = $target->title ?? $target->name ?? $target->name_job ?? "رقم {$target->id}";
        return [
            'user_id' => $owner->id,
            'issuer_type' => $issuer ? class_basename($issuer) : null,
            'issuer_id' => $issuer?->id,
            'warningable_type' => get_class($target),
            'warningable_id' => $target->id,
            'message' => "تحذير بخصوص {$label} «{$name}»: {$message}",
        ];
    }

    public function deleteTarget(int $id)
    {
        $report = Report::findOrFail($id);
        $target = $report->reportable ?: ($report->post_id ? Post::findOrFail($report->post_id) : null);
        if (!$target) abort(404);

        if ($target instanceof Business) {
            $target->user?->deleteServicesWithFiles();
            if ($target->image) Storage::disk('public')->delete($target->image);
        } elseif ($target instanceof Service && $target->image) {
            Storage::disk('public')->delete($target->image);
        }
        $target->delete();
        return redirect()->route(request()->routeIs('super_admin.*') ? 'super_admin.reports.index' : 'admin.reports.index')->with('success', 'تم حذف العنصر المبلّغ عنه.');
    }
}

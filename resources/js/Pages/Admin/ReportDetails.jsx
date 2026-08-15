import AdminLayout from '../../Layouts/AdminLayout';
import ReportDetailsView from '../../Components/ReportDetails';

export default function ReportDetails(props) {
    return <ReportDetailsView {...props} Layout={AdminLayout} backHref="/admin/reports" actionPrefix="/admin/reports" />;
}

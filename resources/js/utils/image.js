export function storageUrl(path) {
    if (!path || typeof path !== 'string') return null;

    const trimmed = path.trim();
    if (!trimmed) return null;

    if (trimmed.startsWith('http://') || trimmed.startsWith('https://')) {
        return trimmed;
    }

    const normalized = trimmed.replace(/^\/+/, '');
    const cleaned = normalized.replace(/^storage\//, '');

    if (
        cleaned.startsWith('avatars/') ||
        cleaned.startsWith('images/') ||
        cleaned.startsWith('businesses/') ||
        cleaned.startsWith('services/') ||
        cleaned.startsWith('uploads/')
    ) {
        return `/storage/${cleaned}`;
    }

    return `/storage/${cleaned}`;
}

export function avatarUrl(user) {
    if (!user) return null;

    const business = user.business || null;
    const businesses = Array.isArray(user.businesses) ?
        user.businesses :
        (user.businesses ? [user.businesses] : []);

    const candidates = [
        user.profile_photo,
        user.profile_photo_url,
        user.avatar,
        user.avatar_url,
        business && business.image,
        business && business.profile_photo,
        ...businesses.map(item => item && item.image),
        ...businesses.map(item => item && item.profile_photo),
    ].filter(Boolean);

    for (const candidate of candidates) {
        const resolved = storageUrl(candidate);
        if (resolved) return resolved;
    }

    return null;
}
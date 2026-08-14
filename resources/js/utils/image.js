export function storageUrl(path) {
    if (!path) return null;

    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }

    const normalized = path.replace(/^\/+/, '');
    const businessAvatar = normalized.replace(
        /^avatars\/business\/business-(?:male|female)-/,
        'avatars/business/business-'
    );

    if (businessAvatar.startsWith('storage/')) {
        return `/${businessAvatar}`;
    }

    if (businessAvatar.startsWith('avatars/business/')) {
        return `/${businessAvatar}`;
    }

    return `/storage/${businessAvatar}`;
}

export function avatarUrl(user) {
    if (!user) return null;
    const path = user.profile_photo ||
        (user.business && user.business.image) ||
        (user.businesses && user.businesses.image) ||
        null;
    return storageUrl(path);
}
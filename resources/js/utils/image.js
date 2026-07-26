export function storageUrl(path) {
    if (!path) return null;
    return path.startsWith('http') ? path : `/storage/${path}`;
}

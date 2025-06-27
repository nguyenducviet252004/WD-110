import { Middleware } from '@reduxjs/toolkit';

export const authMiddleware: Middleware = store => next => action => {
    const token = localStorage.getItem('token');

    if (token) {
        // Chỉnh sửa fetch để luôn thêm token vào headers
        const originalFetch = window.fetch;
        window.fetch = async (input, init) => {
            init = init || {};
            init.headers = init.headers || {};

            if (Array.isArray(init.headers)) {
                init.headers.push(['Authorization', `Bearer ${token}`]);
            } else if (init.headers instanceof Headers) {
                init.headers.set('Authorization', `Bearer ${token}`);
            } else {
                (init.headers as Record<string, string>)['Authorization'] = `Bearer ${token}`;
            }

            return originalFetch(input, init);
        };
    }

    return next(action);
};

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Microposts</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
        @vite('resources/css/app.css')
        
<style>
    .markdown-content h1 {
        font-size: 2.25rem; /* Tailwind CSSのtext-3xlに相当 */
        font-weight: 700;   /* Tailwind CSSのfont-boldに相当 */
        margin-top: 1rem;   /* Tailwind CSSのmt-4に相当 */
        margin-bottom: 0.5rem; /* Tailwind CSSのmb-2に相当 */
    }

    .markdown-content h2 {
        font-size: 1.875rem; /* Tailwind CSSのtext-2xlに相当 */
        font-weight: 700;    /* Tailwind CSSのfont-boldに相当 */
        margin-top: 0.75rem; /* Tailwind CSSのmt-3に相当 */
        margin-bottom: 0.5rem; /* Tailwind CSSのmb-2に相当 */
    }

    .markdown-content h3 {
        font-size: 1.5rem; /* Tailwind CSSのtext-xlに相当 */
        font-weight: 700;  /* Tailwind CSSのfont-boldに相当 */
        margin-top: 0.5rem; /* Tailwind CSSのmt-2に相当 */
        margin-bottom: 0.5rem; /* Tailwind CSSのmb-2に相当 */
    }

    .markdown-content p {
        margin-bottom: 1rem; /* Tailwind CSSのmb-4に相当 */
    }

    .markdown-content strong {
        font-weight: 700; /* Tailwind CSSのfont-boldに相当 */
    }

    .markdown-content em {
        font-style: italic; /* Tailwind CSSのitalicに相当 */
    }

    .markdown-content ul {
        list-style-type: disc; /* Tailwind CSSのlist-discに相当 */
        padding-left: 1.5rem;  /* Tailwind CSSのpl-6に相当 */
        margin-bottom: 1rem;   /* Tailwind CSSのmb-4に相当 */
    }

    .markdown-content ol {
        list-style-type: decimal; /* Tailwind CSSのlist-decimalに相当 */
        padding-left: 1.5rem;     /* Tailwind CSSのpl-6に相当 */
        margin-bottom: 1rem;      /* Tailwind CSSのmb-4に相当 */
    }

    .markdown-content li {
        margin-bottom: 0.5rem; /* Tailwind CSSのmb-2に相当 */
    }

    .markdown-content pre {
        background-color: #f3f4f6; /* Tailwind CSSのbg-gray-100に相当 */
        padding: 1rem;             /* Tailwind CSSのp-4に相当 */
        border-radius: 0.25rem;    /* Tailwind CSSのroundedに相当 */
        margin-bottom: 1rem;       /* Tailwind CSSのmb-4に相当 */
    }

    .markdown-content code {
        background-color: #e5e7eb; /* Tailwind CSSのbg-gray-200に相当 */
        padding: 0.25rem;          /* Tailwind CSSのp-1に相当 */
        border-radius: 0.25rem;    /* Tailwind CSSのroundedに相当 */
    }

    .markdown-content a {
        color: #3b82f6; /* Tailwind CSSのtext-blue-500に相当 */
        text-decoration: underline; /* Tailwind CSSのhover:underlineに相当 */
    }

    .markdown-content blockquote {
        border-left: 0.25rem solid #d1d5db; /* Tailwind CSSのborder-l-4 border-gray-300に相当 */
        padding-left: 1rem;                /* Tailwind CSSのpl-4に相当 */
        font-style: italic;                /* Tailwind CSSのitalicに相当 */
        color: #4b5563;                    /* Tailwind CSSのtext-gray-600に相当 */
        margin-bottom: 1rem;               /* Tailwind CSSのmb-4に相当 */
    }
</style>
    </head>

    <body>

        {{-- ナビゲーションバー --}}
        @include('commons.navbar')

        <div class="container mx-auto">
            {{-- エラーメッセージ --}}
            @include('commons.error_messages')

            @yield('content')
        </div>

    </body>
</html>

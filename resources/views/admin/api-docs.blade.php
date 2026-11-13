@extends('admin.layouts.app')

@section('header', 'API documentation')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-api-docs.css') }}">

<div class="api-docs" id="apiDocs">
    <section class="api-docs-hero">
        <div>
            <span class="api-docs-eyebrow">YSLAM PORTAL · API V1</span>
            <h1>API reference</h1>
            <p>Browse the public API, learn how authentication works, and send the same requests from Bruno.</p>
        </div>
        <div class="api-docs-version">20 endpoints <span>·</span> v1</div>
    </section>

    <section class="api-docs-panel api-docs-setup">
        <div class="api-docs-panel-heading">
            <div>
                <h2>Request setup</h2>
                <p>These values update the cURL examples below. The API accepts JSON.</p>
            </div>
        </div>
        <div class="api-docs-fields">
            <label>
                <span>API base URL</span>
                <input id="apiBaseUrl" type="url" value="{{ url('/api/v1') }}" autocomplete="url">
            </label>
            <label>
                <span>Locale</span>
                <select id="apiLocale">
                    <option value="tm">Turkmen (tm)</option>
                    <option value="ru">Russian (ru)</option>
                    <option value="en">English (en)</option>
                </select>
            </label>
            <label>
                <span>Sanctum token <small>(optional)</small></span>
                <input id="apiToken" type="password" placeholder="Paste a token for protected requests" autocomplete="off">
            </label>
        </div>
        <p class="api-docs-note">Do not share or commit access tokens. The token field only builds a sample command in this browser; this page does not send API requests.</p>
    </section>

    <section class="api-docs-panel api-docs-bruno">
        <div class="api-docs-panel-heading">
            <div>
                <h2>Use this collection in Bruno</h2>
                <p>The importable collection is in the <code>bruno</code> folder in the project.</p>
            </div>
        </div>
        <ol class="api-docs-steps">
            <li><span>1</span><div><strong>Open the collection</strong><p>In Bruno choose <b>Open Collection</b> and select the <code>bruno</code> folder.</p></div></li>
            <li><span>2</span><div><strong>Select the Local environment</strong><p>Set <code>baseUrl</code> to your running app's origin, for example <code>http://127.0.0.1:8000</code>.</p></div></li>
            <li><span>3</span><div><strong>Get an API token</strong><p>Run <code>Auth / Send OTP</code>, copy its code into Local's <code>otpCode</code> variable, then run <code>Auth / Verify OTP</code>. Save the response's <code>token</code> as a Local secret named <code>token</code> in Bruno's Secrets tab.</p></div></li>
            <li><span>4</span><div><strong>Send protected requests</strong><p>Run requests marked <span class="api-docs-badge api-docs-badge-auth">Bearer token</span>. The profile, logout, and comment creation requests use this token.</p></div></li>
        </ol>
        <div class="api-docs-callout"><strong>OTP note:</strong> The current implementation returns the code in the response for testing, and expires it after 10 minutes. Do not rely on that behavior in a production authentication flow.</div>
    </section>

    <section class="api-docs-panel">
        <div class="api-docs-panel-heading api-docs-endpoints-heading">
            <div>
                <h2>API endpoints</h2>
                <p>Locale can be set with <code>?lang=tm</code> or the <code>X-Locale</code> header. Supported values: <code>tm</code>, <code>ru</code>, <code>en</code>.</p>
            </div>
            <label class="api-docs-search">
                <span class="visually-hidden">Search API endpoints</span>
                <input id="apiSearch" type="search" placeholder="Search endpoints">
            </label>
        </div>
        <div class="api-docs-filter" role="group" aria-label="Filter endpoints">
            <button type="button" class="is-active" data-filter="all">All</button>
            <button type="button" data-filter="auth">Authentication</button>
            <button type="button" data-filter="articles">Articles &amp; comments</button>
            <button type="button" data-filter="content">Categories, tags &amp; banners</button>
            <button type="button" data-filter="profile">Profile</button>
        </div>
        <div id="apiEndpointList" class="api-docs-endpoint-list" aria-live="polite"></div>
        <p id="apiNoResults" class="api-docs-empty" hidden>No endpoints match this search.</p>
        <div class="api-docs-response-notes">
            <h3>Responses and limits</h3>
            <ul>
                <li>Paginated lists return <code>data</code>, <code>links</code>, and <code>meta</code>; a single resource is returned under <code>data</code>.</li>
                <li>Protected routes require <code>Authorization: Bearer &lt;token&gt;</code>. Common responses: <code>401</code> unauthenticated, <code>404</code> missing article/category, <code>422</code> invalid input, <code>429</code> rate limited.</li>
                <li>OTP send/verify and comment creation have rate limits. New comments are pending moderation; the public comments endpoint returns approved comments only.</li>
            </ul>
        </div>
    </section>

    <section class="api-docs-panel">
        <div class="api-docs-panel-heading">
            <div>
                <h2>Admin panel is web-based</h2>
                <p>The application has no separate admin JSON API. These routes render HTML and use the signed-in browser session plus CSRF protection; use the admin panel in a browser, not as Bruno bearer-token requests. Resource forms use GET, POST, PUT/PATCH, and DELETE according to the action.</p>
            </div>
        </div>
        <div class="api-docs-table-wrap">
            <table class="api-docs-admin-table">
                <thead><tr><th>Admin area</th><th>Path</th><th>Access</th></tr></thead>
                <tbody>
                    <tr><td>Sign in</td><td><code>GET /admin/login</code>, <code>POST /admin/login</code></td><td>Guest</td></tr>
                    <tr><td>Dashboard</td><td><code>GET /admin</code></td><td>Staff</td></tr>
                    <tr><td>Articles</td><td><code>/admin/articles</code></td><td>Admin, editor, author</td></tr>
                    <tr><td>Categories, tags, banners</td><td><code>/admin/categories</code>, <code>/admin/tags</code>, <code>/admin/banners</code></td><td>Admin, editor</td></tr>
                    <tr><td>Comments</td><td><code>/admin/comments</code></td><td>Admin, editor, moderator</td></tr>
                    <tr><td>Users</td><td><code>/admin/users</code></td><td>Admin</td></tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    (() => {
        const root = document.getElementById('apiDocs');

        if (!root) {
            return;
        }

        const endpoints = [
            { group: 'auth', method: 'POST', path: '/auth/send-otp', name: 'Send OTP', auth: false, description: 'Request a one-time code. Required: name; optional: phone.', body: { name: 'Example Reader', phone: '+99360000000' } },
            { group: 'auth', method: 'POST', path: '/auth/verify-otp', name: 'Verify OTP', auth: false, description: 'Verify the code and receive a Sanctum bearer token. Required: name and code; optional: phone.', body: { name: 'Example Reader', code: '1234' } },
            { group: 'auth', method: 'POST', path: '/auth/login', name: 'Login (OTP alias)', auth: false, description: 'Compatibility alias for OTP verification.', body: { name: 'Example Reader', code: '1234' } },
            { group: 'auth', method: 'POST', path: '/auth/register', name: 'Register (OTP alias)', auth: false, description: 'Compatibility alias for OTP verification and reader auto-registration.', body: { name: 'Example Reader', code: '1234' } },
            { group: 'auth', method: 'GET', path: '/auth/me', name: 'Current authenticated user', auth: true, description: 'Return the current token owner.' },
            { group: 'auth', method: 'POST', path: '/auth/logout', name: 'Revoke current token', auth: true, description: 'Delete the current Sanctum token.' },
            { group: 'articles', method: 'GET', path: '/articles', name: 'List articles', auth: false, description: 'Paginated published articles. Optional: limit (1–20), page, category, tag, featured, search, sort=latest|popular.', query: '?limit=12&lang=tm' },
            { group: 'articles', method: 'GET', path: '/articles/popular', name: 'Popular articles', auth: false, description: 'Return up to six popular published articles.' },
            { group: 'articles', method: 'GET', path: '/articles/trending', name: 'Trending articles', auth: false, description: 'Return up to six trending published articles.' },
            { group: 'articles', method: 'GET', path: '/articles/featured', name: 'Featured article', auth: false, description: 'Return the featured article, or the latest published one if none is featured.' },
            { group: 'articles', method: 'GET', path: '/articles/{slug}', name: 'Get article', auth: false, description: 'Return a published article and related articles.', examplePath: '/articles/example-article' },
            { group: 'articles', method: 'GET', path: '/articles/{slug}/comments', name: 'List approved comments', auth: false, description: 'Paginated approved comments for a published article.', examplePath: '/articles/example-article/comments' },
            { group: 'articles', method: 'POST', path: '/articles/{slug}/comments', name: 'Create comment', auth: true, description: 'Submit a comment for moderation. Required body: body (max 1000 characters).', examplePath: '/articles/example-article/comments', body: { body: 'A sample comment.' } },
            { group: 'content', method: 'GET', path: '/categories', name: 'List categories', auth: false, description: 'Return categories with localized names and translations.' },
            { group: 'content', method: 'GET', path: '/categories/{slug}/articles', name: 'List category articles', auth: false, description: 'Paginated published articles. Optional: limit (1–20), page.', examplePath: '/categories/news/articles', query: '?limit=12' },
            { group: 'content', method: 'GET', path: '/categories/{slug}/popular', name: 'Popular category articles', auth: false, description: 'Paginated published articles ordered by views.', examplePath: '/categories/news/popular', query: '?limit=12' },
            { group: 'content', method: 'GET', path: '/tags', name: 'List tags', auth: false, description: 'Return tags.' },
            { group: 'content', method: 'GET', path: '/banners', name: 'List active banners', auth: false, description: 'Return active banners.' },
            { group: 'profile', method: 'GET', path: '/me', name: 'Get profile', auth: true, description: 'Return the current authenticated user profile.' },
            { group: 'profile', method: 'PUT', path: '/me', name: 'Update profile', auth: true, description: 'Optional fields: name, email, phone, password plus password_confirmation, avatar (image upload or null to remove).', body: { name: 'Updated Reader' } },
        ];

        const list = document.getElementById('apiEndpointList');
        const noResults = document.getElementById('apiNoResults');
        const search = document.getElementById('apiSearch');
        const baseUrl = document.getElementById('apiBaseUrl');
        const locale = document.getElementById('apiLocale');
        const token = document.getElementById('apiToken');
        let activeFilter = 'all';

        function createElement(tag, className, text) {
            const element = document.createElement(tag);

            if (className) {
                element.className = className;
            }

            if (text !== undefined) {
                element.textContent = text;
            }

            return element;
        }

        function requestCommand(endpoint) {
            const url = `${baseUrl.value.replace(/\/+$/, '')}${endpoint.examplePath || endpoint.path.replace('{slug}', 'example-article')}${endpoint.query || ''}`;
            const command = [`curl --request ${endpoint.method} --url '${url}'`, "--header 'Accept: application/json'", `--header 'X-Locale: ${locale.value}'`];

            if (endpoint.auth) {
                command.push(`--header 'Authorization: Bearer ${token.value || 'YOUR_SANCTUM_TOKEN'}'`);
            }

            if (endpoint.body) {
                command.push("--header 'Content-Type: application/json'");
                command.push(`--data '${JSON.stringify(endpoint.body)}'`);
            }

            return command.join(' \\\n  ');
        }

        function renderEndpoint(endpoint) {
            const details = createElement('details', 'api-docs-endpoint');
            const summary = document.createElement('summary');
            const method = endpoint.method.toLowerCase();

            summary.append(
                createElement('span', `api-docs-method api-docs-method-${method}`, endpoint.method),
                createElement('code', 'api-docs-path', endpoint.path),
                createElement('span', 'api-docs-title', endpoint.name),
                createElement('span', `api-docs-badge ${endpoint.auth ? 'api-docs-badge-auth' : 'api-docs-badge-public'}`, endpoint.auth ? 'Bearer token' : 'Public')
            );

            const example = createElement('div', 'api-docs-example');
            example.append(createElement('p', '', endpoint.description));

            const actions = createElement('div', 'api-docs-example-actions');
            actions.append(createElement('strong', '', 'cURL example'));
            const copy = createElement('button', 'api-docs-copy', 'Copy cURL');
            copy.type = 'button';
            copy.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(requestCommand(endpoint));
                    copy.textContent = 'Copied';
                    window.setTimeout(() => {
                        copy.textContent = 'Copy cURL';
                    }, 1500);
                } catch {
                    copy.textContent = 'Copy unavailable';
                }
            });
            actions.append(copy);

            const code = createElement('pre', 'api-docs-command');
            code.textContent = requestCommand(endpoint);
            example.append(actions, code);
            details.append(summary, example);
            details.dataset.group = endpoint.group;
            details.dataset.search = `${endpoint.method} ${endpoint.path} ${endpoint.name} ${endpoint.description}`.toLowerCase();

            return details;
        }

        endpoints.forEach((endpoint) => list.append(renderEndpoint(endpoint)));

        function filterEndpoints() {
            const term = search.value.trim().toLowerCase();
            let visibleCount = 0;

            list.querySelectorAll('.api-docs-endpoint').forEach((endpoint) => {
                const matchesGroup = activeFilter === 'all' || endpoint.dataset.group === activeFilter;
                const matchesSearch = endpoint.dataset.search.includes(term);
                endpoint.hidden = !(matchesGroup && matchesSearch);

                if (!endpoint.hidden) {
                    visibleCount += 1;
                }
            });

            noResults.hidden = visibleCount > 0;
        }

        document.querySelectorAll('.api-docs-filter button').forEach((button) => {
            button.addEventListener('click', () => {
                activeFilter = button.dataset.filter;
                document.querySelectorAll('.api-docs-filter button').forEach((filterButton) => {
                    filterButton.classList.toggle('is-active', filterButton === button);
                });
                filterEndpoints();
            });
        });

        search.addEventListener('input', filterEndpoints);

        function refreshCommands() {
            list.querySelectorAll('.api-docs-endpoint').forEach((endpoint, index) => {
                endpoint.querySelector('.api-docs-command').textContent = requestCommand(endpoints[index]);
            });
        }

        [baseUrl, locale, token].forEach((input) => input.addEventListener('input', refreshCommands));
        locale.addEventListener('change', refreshCommands);
    })();
</script>
@endsection

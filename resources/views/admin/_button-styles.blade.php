<style>
/* Centralized admin button styles */
:root{
    --admin-positive: #2F9E4A; /* green */
    --admin-positive-hover: #247e39;
    --admin-negative: #DC2626; /* red-600 */
    --admin-negative-hover: #b91c1c;
    --admin-neutral-bg: #e5e7eb; /* gray-200 */
    --admin-neutral-text: #5b1b1b; /* maroon-like */
}

.admin-btn-positive{
    background-color: var(--admin-positive);
    color: #ffffff;
}
.admin-btn-positive:hover{ background-color: var(--admin-positive-hover); }

.admin-btn-negative{
    background-color: var(--admin-negative);
    color: #ffffff;
}
.admin-btn-negative:hover{ background-color: var(--admin-negative-hover); }

.admin-btn-neutral{
    background-color: var(--admin-neutral-bg);
    color: var(--admin-neutral-text);
}
.admin-btn-neutral:hover{ filter: brightness(0.95); }

/* Keep focus ring similar to existing UX */
.admin-btn-positive:focus, .admin-btn-negative:focus, .admin-btn-neutral:focus{
    outline: none;
    box-shadow: 0 0 0 3px rgba(45, 163, 82, 0.18);
}
</style>

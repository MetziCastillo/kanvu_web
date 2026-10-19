<script setup>
    defineProps({
        name: {
            type: String,
            required: true,
        },
        size: {
            type: [Number, String],
            default: 22,
        },
        color: {
            type: String,
            default: 'currentColor',
        },
        strokeWidth: {
            type: [Number, String],
            default: 1.6
        },
    });
</script>

<template>
    <svg v-if="name === 'leaf'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 22 22"
        fill="none">
        <path
            d="M15.5833 7.33331C7.33334 9.16665 5.40834 14.8225 3.50168 17.7283C3.37963 17.9039 3.32281 18.1166 3.34105 18.3297C3.35929 18.5427 3.45144 18.7427 3.60155 18.895C3.75166 19.0473 3.95029 19.1423 4.16306 19.1636C4.37583 19.1849 4.58935 19.1312 4.76668 19.0116C6.95751 17.4166 13.0533 14.6666 15.5833 7.33331Z"
            stroke="color" stroke-width="strokeWidth" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M15.5833 7.33331C15.5833 7.33331 11.9167 15.5833 2.75 17.4166" :stroke="color" stroke-width="1.60417"
            stroke-linecap="round" stroke-linejoin="round" />
    </svg>

    <svg v-if="name === 'Menu'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 22 22"
        fill="none">
        <path d="M2.75 5.5H19.25" stroke-width="1.60417" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
        <path d="M2.75 11H19.25" stroke-width="1.60417" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
        <path d="M2.75 16.5H19.25" stroke-width="1.60417" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
    </svg>

    <svg v-if="name === 'Bell'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 20 20"
        fill="none">
        <path
            d="M15 6.66669C15 5.3406 14.4732 4.06883 13.5355 3.13115C12.5979 2.19347 11.3261 1.66669 10 1.66669C8.67392 1.66669 7.40215 2.19347 6.46447 3.13115C5.52678 4.06883 5 5.3406 5 6.66669C5 12.5 2.5 14.1667 2.5 14.1667H17.5C17.5 14.1667 15 12.5 15 6.66669Z"
            stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path
            d="M11.4417 17.5C11.2951 17.7526 11.0849 17.9622 10.8318 18.1079C10.5788 18.2537 10.292 18.3304 9.99999 18.3304C9.708 18.3304 9.42114 18.2537 9.16813 18.1079C8.91512 17.9622 8.70483 17.7526 8.55832 17.5"
            stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'plus'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 18 18"
        fill="none">
        <path d="M9 3.75V9M9 9V14.25M9 9H3.75M9 9H14.25" stroke-width="1.3125" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'calendar'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 26 26"
        fill="none">
        <path
            d="M6.5 4.33334H19.5C21.2949 4.33334 22.75 5.78842 22.75 7.58334V20.5833C22.75 22.3783 21.2949 23.8333 19.5 23.8333H6.5C4.70507 23.8333 3.25 22.3783 3.25 20.5833V7.58334C3.25 5.78842 4.70507 4.33334 6.5 4.33334Z"
            stroke-width="1.89583" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M17.3333 2.16666V6.49999M8.66667 2.16666V6.49999M3.25 10.8333H22.75" stroke-width="1.89583"
            stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'check'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 20 14"
        fill="none">
        <path d="M18.2813 0.947906L6.36458 12.8646L0.947918 7.44791" stroke-width="1.89583" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'pending'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 24 24"
        fill="none" :stroke="color" stroke-width="1.89583" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-clipboard-clock preview-icon">
        <path d="M16 14v2.2l1.6 1" />
        <path d="M16 4h2a2 2 0 0 1 2 2v.832" />
        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2" />
        <circle cx="16" cy="16" r="6" />
        <rect x="8" y="2" width="8" height="4" rx="1" />
    </svg>

    <svg v-if="name === 'clock'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 26 26"
        fill="none">
        <path
            d="M22.75 13C22.75 18.3848 18.3848 22.75 13 22.75C7.61522 22.75 3.25 18.3848 3.25 13C3.25 7.61522 7.61522 3.25 13 3.25C18.3848 3.25 22.75 7.61522 22.75 13Z"
            stroke-width="1.89583" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M13 7.58334V13L16.25 16.25" stroke-width="1.89583" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
    </svg>

    <svg v-if="name === 'mapPin'"  xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 14 14" fill="none">
        <path
            d="M7 1.16667C8.08297 1.16667 9.12158 1.59688 9.88736 2.36265C10.6531 3.12843 11.0833 4.16704 11.0833 5.25C11.0833 8.16667 7 12.8333 7 12.8333C7 12.8333 2.91667 8.16667 2.91667 5.25C2.91667 4.16704 3.34688 3.12843 4.11265 2.36265C4.87843 1.59688 5.91704 1.16667 7 1.16667Z"
            stroke-width="1.02083" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path
            d="M8.45834 5.25001C8.45834 6.05542 7.80542 6.70834 7.00001 6.70834C6.19459 6.70834 5.54167 6.05542 5.54167 5.25001C5.54167 4.44459 6.19459 3.79167 7.00001 3.79167C7.80542 3.79167 8.45834 4.44459 8.45834 5.25001Z"
            stroke-width="1.02083" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>
</template>
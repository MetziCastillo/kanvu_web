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

    <svg v-if="name === 'mapPin'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 14 14"
        fill="none">
        <path
            d="M7 1.16667C8.08297 1.16667 9.12158 1.59688 9.88736 2.36265C10.6531 3.12843 11.0833 4.16704 11.0833 5.25C11.0833 8.16667 7 12.8333 7 12.8333C7 12.8333 2.91667 8.16667 2.91667 5.25C2.91667 4.16704 3.34688 3.12843 4.11265 2.36265C4.87843 1.59688 5.91704 1.16667 7 1.16667Z"
            stroke-width="1.02083" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path
            d="M8.45834 5.25001C8.45834 6.05542 7.80542 6.70834 7.00001 6.70834C6.19459 6.70834 5.54167 6.05542 5.54167 5.25001C5.54167 4.44459 6.19459 3.79167 7.00001 3.79167C7.80542 3.79167 8.45834 4.44459 8.45834 5.25001Z"
            stroke-width="1.02083" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'users'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 25 25"
        fill="none">
        <g clip-path="url(#clip0_38_316)">
            <path
                d="M17.7084 21.875V19.7917C17.7084 18.6866 17.2694 17.6268 16.488 16.8454C15.7066 16.064 14.6468 15.625 13.5417 15.625H5.20835C4.10328 15.625 3.04348 16.064 2.26208 16.8454C1.48067 17.6268 1.04169 18.6866 1.04169 19.7917V21.875"
                stroke-width="1.82292" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
            <path
                d="M13.5416 7.29167C13.5416 9.59285 11.6762 11.4583 9.37498 11.4583C7.07379 11.4583 5.20831 9.59285 5.20831 7.29167C5.20831 4.99048 7.07379 3.125 9.37498 3.125C11.6762 3.125 13.5416 4.99048 13.5416 7.29167Z"
                stroke-width="1.82292" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
            <path
                d="M23.9583 21.875V19.7917C23.9576 18.8685 23.6504 17.9717 23.0847 17.242C22.5191 16.5124 21.7272 15.9912 20.8333 15.7604"
                stroke-width="1.82292" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
            <path
                d="M16.6667 3.26044C17.563 3.48992 18.3574 4.01117 18.9246 4.74201C19.4919 5.47285 19.7999 6.37172 19.7999 7.2969C19.7999 8.22207 19.4919 9.12094 18.9246 9.85178C18.3574 10.5826 17.563 11.1039 16.6667 11.3334"
                stroke-width="1.82292" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        </g>
        <defs>
            <clipPath id="clip0_38_316">
                <rect width="25" height="25" fill="white" />
            </clipPath>
        </defs>
    </svg>

    <svg v-if="name === 'lock'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 25 25"
        fill="none">
        <path
            d="M7.29171 11.4583H17.7084C18.859 11.4583 19.7917 12.3911 19.7917 13.5416V19.7916C19.7917 20.9422 18.859 21.875 17.7084 21.875H7.29171C6.14111 21.875 5.20837 20.9422 5.20837 19.7916V13.5416C5.20837 12.3911 6.14111 11.4583 7.29171 11.4583Z"
            stroke-width="1.82292" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path
            d="M8.33337 11.4583V7.29167C8.33337 6.1866 8.77236 5.12679 9.55376 4.34539C10.3352 3.56399 11.395 3.125 12.5 3.125C13.6051 3.125 14.6649 3.56399 15.4463 4.34539C16.2277 5.12679 16.6667 6.1866 16.6667 7.29167V11.4583"
            stroke-width="1.82292" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'graduationCap' " xmlns="http://www.w3.org/2000/svg" :width="size" :height="size"
        viewBox="0 0 25 25" fill="none">
        <path d="M22.9167 10.4167L12.5 4.16669L2.08337 10.4167L12.5 16.6667L22.9167 10.4167Z" stroke-width="1.82292"
            stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path
            d="M6.25 13.0208V17.7083C6.25 19.2708 9.0625 20.8333 12.5 20.8333C15.9375 20.8333 18.75 19.2708 18.75 17.7083V13.0208"
            stroke-width="1.82292" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'boocksOpen'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size"
        viewBox="0 0 18 18" fill="none">
        <path
            d="M1.5 2.25H6C6.79565 2.25 7.55871 2.56607 8.12132 3.12868C8.68393 3.69129 9 4.45435 9 5.25V15.75C9 15.1533 8.76295 14.581 8.34099 14.159C7.91903 13.7371 7.34674 13.5 6.75 13.5H1.5V2.25Z"
            stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path
            d="M16.5 2.25H12C11.2044 2.25 10.4413 2.56607 9.87868 3.12868C9.31607 3.69129 9 4.45435 9 5.25V15.75C9 15.1533 9.23705 14.581 9.65901 14.159C10.081 13.7371 10.6533 13.5 11.25 13.5H16.5V2.25Z"
            stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'user'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 18 18"
        fill="none">
        <path
            d="M12 6C12 7.65685 10.6569 9 9 9C7.34315 9 6 7.65685 6 6C6 4.34315 7.34315 3 9 3C10.6569 3 12 4.34315 12 6Z"
            stroke-width="1.3125" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M3 15C3 12 5.7 9.75 9 9.75C12.3 9.75 15 12 15 15" stroke-width="1.3125" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'chevronRight' " xmlns="http://www.w3.org/2000/svg" :width="size" :height="size"
        viewBox="0 0 15 15" fill="none">
        <path d="M5.625 11.25L9.375 7.5L5.625 3.75" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
    </svg>

    <svg v-if="name === 'shield' " xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 13 19"
        fill="none">
        <path
            d="M6.28125 14.7344C6.28125 14.7344 10.4688 12.6406 10.4688 9.5V5.83594L6.28125 4.26562L2.09375 5.83594V9.5C2.09375 12.6406 6.28125 14.7344 6.28125 14.7344Z"
            stroke-width="0.916016" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'lightbulb'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 11 18"
        fill="none">
        <path d="M3.93164 11.6211H6.55274" stroke-width="0.764488" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
        <path d="M4.36853 13.3685H6.11593" stroke-width="0.764488" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
        <path
            d="M6.59206 9.87367C6.67069 9.44556 6.87601 9.11355 7.20802 8.78154C7.41896 8.58737 7.5864 8.35073 7.69934 8.08721C7.81228 7.82368 7.86816 7.53924 7.86329 7.25257C7.86329 6.55741 7.58714 5.89072 7.09559 5.39917C6.60404 4.90762 5.93735 4.63147 5.24219 4.63147C4.54703 4.63147 3.88035 4.90762 3.3888 5.39917C2.89724 5.89072 2.62109 6.55741 2.62109 7.25257C2.62109 7.68942 2.72157 8.22675 3.27637 8.78154C3.59267 9.07077 3.80845 9.45336 3.89233 9.87367"
            stroke-width="0.764488" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'history'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 16 16"
        fill="none">
        <path
            d="M2 8C2 9.18669 2.35189 10.3467 3.01118 11.3334C3.67047 12.3201 4.60754 13.0892 5.7039 13.5433C6.80026 13.9974 8.00666 14.1162 9.17054 13.8847C10.3344 13.6532 11.4035 13.0818 12.2426 12.2426C13.0818 11.4035 13.6532 10.3344 13.8847 9.17054C14.1162 8.00666 13.9974 6.80026 13.5433 5.7039C13.0892 4.60754 12.3201 3.67047 11.3334 3.01118C10.3467 2.35189 9.18669 2 8 2C6.32263 2.00631 4.71265 2.66082 3.50667 3.82667L2 5.33333"
            stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M2 2V5.33333H5.33333" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
        <path d="M8 4.66663V7.99996L10.6667 9.33329" stroke-width="1.16667" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'monitor'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 15 15" fill="none">
        <path
            d="M2.5 1.875H12.5C13.1904 1.875 13.75 2.43464 13.75 3.125V9.375C13.75 10.0654 13.1904 10.625 12.5 10.625H2.5C1.80964 10.625 1.25 10.0654 1.25 9.375V3.125C1.25 2.43464 1.80964 1.875 2.5 1.875Z"
            stroke-width="1.09375" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M5 13.125H7.5M7.5 13.125H10M7.5 13.125V10.625" stroke-width="1.09375" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
    </svg>
</template>
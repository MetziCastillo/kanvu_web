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

    <svg v-if="name === 'leaf'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 20 20"
        fill="none">
        <path
            d="M14.1667 6.66666C6.6667 8.33333 4.9167 13.475 3.18337 16.1167C3.07241 16.2763 3.02076 16.4697 3.03734 16.6634C3.05392 16.857 3.13769 17.0388 3.27416 17.1773C3.41062 17.3157 3.59119 17.4021 3.78462 17.4215C3.97805 17.4408 4.17216 17.392 4.33336 17.2833C6.32503 15.8333 11.8667 13.3333 14.1667 6.66666Z"
            stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M14.1667 6.66666C14.1667 6.66666 10.8333 14.1667 2.5 15.8333" stroke-width="1.45833"
            stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
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

    <svg v-if="name === 'chevronLeft'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size"
        viewBox="0 0 18 18" fill="none">
        <path d="M11.25 13.5L6.75 9L11.25 4.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
    </svg>

    <svg v-if="name === 'shield'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 16 16"
        fill="none">
        <path
            d="M7.99996 14.6667C7.99996 14.6667 13.3333 12 13.3333 8.00004V3.33337L7.99996 1.33337L2.66663 3.33337V8.00004C2.66663 12 7.99996 14.6667 7.99996 14.6667Z"
            stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
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

    <svg v-if="name === 'monitor'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 15 15"
        fill="none">
        <path
            d="M2.5 1.875H12.5C13.1904 1.875 13.75 2.43464 13.75 3.125V9.375C13.75 10.0654 13.1904 10.625 12.5 10.625H2.5C1.80964 10.625 1.25 10.0654 1.25 9.375V3.125C1.25 2.43464 1.80964 1.875 2.5 1.875Z"
            stroke-width="1.09375" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M5 13.125H7.5M7.5 13.125H10M7.5 13.125V10.625" stroke-width="1.09375" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'close'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 18 18"
        fill="none">
        <path d="M13.5 4.5L9 9M9 9L4.5 13.5M9 9L4.5 4.5M9 9L13.5 13.5" stroke-width="1.5" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
    </svg>


    <svg v-if="name === 'settings'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 20 20"
        fill="none">
        <g clip-path="url(#clip0_134_290)">
            <path
                d="M12.5 10C12.5 11.3807 11.3807 12.5 10 12.5C8.61929 12.5 7.5 11.3807 7.5 10C7.5 8.61929 8.61929 7.5 10 7.5C11.3807 7.5 12.5 8.61929 12.5 10Z"
                stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
            <path
                d="M16.1668 12.5C16.0559 12.7514 16.0228 13.0302 16.0718 13.3005C16.1208 13.5708 16.2497 13.8203 16.4418 14.0167L16.4918 14.0667C16.8046 14.3794 16.9803 14.8036 16.9803 15.2458C16.9803 15.6881 16.8046 16.1123 16.4918 16.425C16.1791 16.7377 15.7549 16.9134 15.3127 16.9134C14.8704 16.9134 14.4462 16.7377 14.1335 16.425L14.0835 16.375C13.8871 16.1829 13.6376 16.054 13.3673 16.005C13.097 15.956 12.8182 15.9891 12.5668 16.1C12.3204 16.2056 12.1101 16.381 11.9621 16.6046C11.814 16.8282 11.7346 17.0902 11.7335 17.3583V17.5C11.7335 17.942 11.5579 18.366 11.2453 18.6785C10.9328 18.9911 10.5089 19.1667 10.0668 19.1667C9.6248 19.1667 9.20088 18.9911 8.88832 18.6785C8.57576 18.366 8.40016 17.942 8.40016 17.5V17.425C8.39371 17.1492 8.30443 16.8817 8.14392 16.6573C7.98341 16.4328 7.75911 16.2619 7.50016 16.1667C7.24882 16.0557 6.97 16.0227 6.69967 16.0717C6.42934 16.1207 6.17989 16.2496 5.9835 16.4417L5.9335 16.4917C5.62076 16.8044 5.1966 16.9801 4.75433 16.9801C4.31206 16.9801 3.8879 16.8044 3.57516 16.4917C3.26243 16.1789 3.08674 15.7548 3.08674 15.3125C3.08674 14.8702 3.26243 14.4461 3.57516 14.1333L3.62516 14.0833C3.83126 13.8816 3.9687 13.6202 4.01805 13.336C4.0674 13.0519 4.02616 12.7594 3.90016 12.5C3.79453 12.2535 3.61913 12.0433 3.39555 11.8953C3.17198 11.7472 2.90998 11.6677 2.64183 11.6667H2.50016C2.05814 11.6667 1.63421 11.4911 1.32165 11.1785C1.00909 10.866 0.833496 10.442 0.833496 10C0.833496 9.55798 1.00909 9.13406 1.32165 8.8215C1.63421 8.50894 2.05814 8.33334 2.50016 8.33334H2.57516C2.84332 8.33227 3.10531 8.25282 3.32888 8.10475C3.55246 7.95669 3.72786 7.74648 3.8335 7.50001C3.94443 7.24866 3.97752 6.96985 3.9285 6.69952C3.87948 6.42919 3.75061 6.17974 3.5585 5.98334L3.5085 5.93334C3.19576 5.62061 3.02007 5.19645 3.02007 4.75418C3.02007 4.3119 3.19576 3.88774 3.5085 3.57501C3.82123 3.26228 4.24539 3.08658 4.68766 3.08658C5.12994 3.08658 5.55409 3.26228 5.86683 3.57501L5.91683 3.62501C6.11857 3.8311 6.38 3.96855 6.66414 4.0179C6.94828 4.06725 7.24075 4.02601 7.50016 3.90001C7.74664 3.79437 7.95684 3.61897 8.10491 3.3954C8.25297 3.17182 8.33243 2.90983 8.3335 2.64168V2.50001C8.3335 2.05798 8.50909 1.63406 8.82165 1.3215C9.13421 1.00894 9.55813 0.833344 10.0002 0.833344C10.4422 0.833344 10.8661 1.00894 11.1787 1.3215C11.4912 1.63406 11.6668 2.05798 11.6668 2.50001V2.57501C11.6679 2.84317 11.7474 3.10516 11.8954 3.32873C12.0435 3.55231 12.2537 3.72771 12.5002 3.83334C12.7515 3.94427 13.0303 3.97736 13.3007 3.92835C13.571 3.87933 13.8204 3.75046 14.0168 3.55834L14.0668 3.50834C14.3796 3.19561 14.8037 3.01992 15.246 3.01992C15.465 3.01992 15.6818 3.06305 15.8842 3.14685C16.0865 3.23066 16.2703 3.35349 16.4252 3.50834C16.58 3.66319 16.7028 3.84703 16.7867 4.04935C16.8705 4.25167 16.9136 4.46852 16.9136 4.68751C16.9136 4.9065 16.8705 5.12335 16.7867 5.32567C16.7028 5.52799 16.58 5.71183 16.4252 5.86668L16.3752 5.91668C16.1799 6.12606 16.0549 6.39116 16.0176 6.675C15.9802 6.95883 16.0324 7.24725 16.1668 7.50001C16.2725 7.74648 16.4479 7.95669 16.6714 8.10475C16.895 8.25282 17.157 8.33227 17.4252 8.33334H17.5002C17.9422 8.33334 18.3661 8.50894 18.6787 8.8215C18.9912 9.13406 19.1668 9.55798 19.1668 10C19.1668 10.442 18.9912 10.866 18.6787 11.1785C18.3661 11.4911 17.9422 11.6667 17.5002 11.6667H17.4252C17.157 11.6677 16.895 11.7472 16.6714 11.8953C16.4479 12.0433 16.2725 12.2535 16.1668 12.5Z"
                stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        </g>
        <defs>
            <clipPath id="clip0_134_290">
                <rect width="20" height="20" fill="white" />
            </clipPath>
        </defs>
    </svg>

    <svg v-if="name === 'fileText'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 20 20"
        fill="none">
        <path
            d="M11.6668 1.66666H5.00016C4.55814 1.66666 4.13421 1.84225 3.82165 2.15481C3.50909 2.46737 3.3335 2.8913 3.3335 3.33332V16.6667C3.3335 17.1087 3.50909 17.5326 3.82165 17.8452C4.13421 18.1577 4.55814 18.3333 5.00016 18.3333H15.0002C15.4422 18.3333 15.8661 18.1577 16.1787 17.8452C16.4912 17.5326 16.6668 17.1087 16.6668 16.6667V6.66666L11.6668 1.66666Z"
            stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M11.6665 1.66666V6.66666H16.6665" stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
        <path d="M13.3332 10.8333H6.6665" stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
        <path d="M13.3332 14.1667H6.6665" stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
    </svg>

    <svg v-if="name === 'logout'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 20 20"
        fill="none">
        <path
            d="M7.5 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V4.16667C2.5 3.72464 2.67559 3.30072 2.98816 2.98816C3.30072 2.67559 3.72464 2.5 4.16667 2.5H7.5"
            stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
        <path d="M13.3335 14.1667L17.5002 10L13.3335 5.83334" stroke-width="1.45833" stroke-linecap="round"
            stroke-linejoin="round" :stroke="color" />
        <path d="M17.5 10H7.5" stroke-width="1.45833" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>

    <svg v-if="name === 'messageCircle'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size"
        viewBox="0 0 16 16" fill="none">
        <path
            d="M14 10C14 10.3536 13.8595 10.6928 13.6095 10.9428C13.3594 11.1929 13.0203 11.3333 12.6667 11.3333H4.66667L2 14V3.33333C2 2.97971 2.14048 2.64057 2.39052 2.39052C2.64057 2.14048 2.97971 2 3.33333 2H12.6667C13.0203 2 13.3594 2.14048 13.6095 2.39052C13.8595 2.64057 14 2.97971 14 3.33333V10Z"
            stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
    </svg>



    <svg v-if="name === 'mail'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 13 13"
        fill="none">
        <g clip-path="url(#clip0_266_184)">
            <path
                d="M2.16659 2.1665H10.8333C11.4316 2.1665 11.9166 2.65153 11.9166 3.24984V9.74984C11.9166 10.3481 11.4316 10.8332 10.8333 10.8332H2.16659C1.56828 10.8332 1.08325 10.3481 1.08325 9.74984V3.24984C1.08325 2.65153 1.56828 2.1665 2.16659 2.1665Z"
                stroke-width="0.947917" stroke-linecap="round" stroke-linejoin="round" :stroke="color" />
            <path d="M1.08325 3.7915L6.49992 7.58317L11.9166 3.7915" stroke-width="0.947917" stroke-linecap="round"
                stroke-linejoin="round" :stroke="color" />
        </g>
        <defs>
            <clipPath id="clip0_266_184">
                <rect :width="size" :height="size" fill="white" />
            </clipPath>
        </defs>
    </svg>


    <svg v-if="name === 'x'" xmlns="http://www.w3.org/2000/svg" :width="size" :height="size" viewBox="0 0 16 16"
        fill="none">
        <path d="M12 4L4 12M4 4L12 12" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"
            :stroke="color" />
    </svg>
</template>
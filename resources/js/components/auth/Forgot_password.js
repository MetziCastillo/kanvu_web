import { ref } from 'vue';

export function useForgotPassword() {
    const email = ref('');
    const send = ref(false);

    return {
        email,
        send,
    };
}

import Alpine from 'alpinejs';

const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const MIN_PASSWORD_LENGTH = 8;

const required = (message) => (value) => (value ? '' : message);

const loginRules = {
    email: (value) => {
        if (!value) return 'Informe o e-mail.';
        return EMAIL_PATTERN.test(value) ? '' : 'Informe um e-mail válido, como nome@exemplo.com.';
    },
    password: required('Informe a senha.'),
};

const registerRules = {
    username: required('Informe o nome de usuário.'),
    name: required('Informe o nome completo.'),
    email: loginRules.email,
    matricula: required('Informe a matrícula.'),
    second_matricula: (value, form) =>
        form.elements.has_second_matricula.checked && !value ? 'Informe a segunda matrícula.' : '',
    rg_uf: required('Selecione a UF do RG.'),
    rg: (value) => {
        if (!value) return 'Informe o RG ou CIN.';
        return value.length < 7 ? 'O RG deve ter entre 7 e 11 caracteres.' : '';
    },
    cpf: (value) => {
        if (!value) return 'Informe o CPF.';
        return value.replace(/\D/g, '').length === 11 ? '' : 'O CPF deve ter 11 números.';
    },
    password: (value) => {
        if (!value) return 'Crie uma senha.';
        return value.length < MIN_PASSWORD_LENGTH ? `A senha precisa ter pelo menos ${MIN_PASSWORD_LENGTH} caracteres.` : '';
    },
    password_confirmation: (value, form) => {
        if (!value) return 'Repita a senha.';
        return value === form.elements.password.value ? '' : 'As senhas não são iguais.';
    },
};

const REGISTER_STEPS = [
    { title: 'Sobre você', fields: ['username', 'name', 'email'] },
    { title: 'Documentos', fields: ['matricula', 'second_matricula', 'rg_uf', 'rg', 'cpf'] },
    { title: 'Senha', fields: ['password', 'password_confirmation'] },
];

const formatCpf = (text) =>
    text
        .replace(/\D/g, '')
        .slice(0, 11)
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');

const sanitizeRg = (text) =>
    text
        .toUpperCase()
        .replace(/[^0-9A-Z]/g, '')
        .slice(0, 11);

// Laravel entrega listas de mensagens por campo; a tela mostra só a primeira.
const firstMessages = (messageLists) =>
    Object.fromEntries(Object.entries(messageLists).map(([field, messages]) => [field, messages[0]]));

const authForm = (serverMessages, rules) => ({
    errors: firstMessages(serverMessages),
    submitting: false,

    clear(field) {
        delete this.errors[field];
    },

    // Valida os campos e devolve o primeiro input inválido (ou null se todos passaram).
    validate(fields) {
        const form = this.$refs.form;
        let firstInvalid = null;

        for (const field of fields) {
            const input = form.elements[field];
            const value = field.startsWith('password') ? input.value : input.value.trim();
            const message = rules[field](value, form);

            if (message) {
                this.errors[field] = message;
                firstInvalid ??= input;
            } else {
                delete this.errors[field];
            }
        }

        return firstInvalid;
    },
});

Alpine.data('loginForm', (serverMessages) => ({
    ...authForm(serverMessages, loginRules),

    submit() {
        const invalidInput = this.validate(['email', 'password']);
        if (invalidInput) return invalidInput.focus();

        this.submitting = true;
        this.$refs.form.submit();
    },
}));

Alpine.data('registerForm', (serverMessages, hasSecondMatricula) => ({
    ...authForm(serverMessages, registerRules),
    steps: REGISTER_STEPS,
    step: 0,
    hasSecondMatricula,

    init() {
        const stepWithError = REGISTER_STEPS.findIndex(({ fields }) => fields.some((field) => this.errors[field]));
        if (stepWithError >= 0) this.step = stepWithError;

        this.$nextTick(() => {
            const { elements } = this.$refs.form;
            // O servidor devolve o CPF só com dígitos; reaplica a máscara.
            elements.cpf.value = formatCpf(elements.cpf.value);

            const invalidField = REGISTER_STEPS[this.step].fields.find((field) => this.errors[field]);
            if (invalidField) elements[invalidField].focus();
        });
    },

    get isLastStep() {
        return this.step === REGISTER_STEPS.length - 1;
    },

    // Navegar entre etapas é livre, pela barra ou pelos botões; a validação cobra no Continuar e no envio.
    goTo(index) {
        this.step = index;
    },

    stepHasError(index) {
        return REGISTER_STEPS[index].fields.some((field) => this.errors[field]);
    },

    next() {
        const invalidInput = this.validate(REGISTER_STEPS[this.step].fields);
        if (invalidInput) return invalidInput.focus();

        if (!this.isLastStep) {
            this.goTo(this.step + 1);
            this.$nextTick(() => this.$refs.form.elements[REGISTER_STEPS[this.step].fields[0]].focus());
            return;
        }

        // Quem pulou etapas pela barra só descobre aqui: abre a primeira etapa com problema.
        const invalidByStep = REGISTER_STEPS.map(({ fields }) => this.validate(fields));
        const firstInvalidStep = invalidByStep.findIndex(Boolean);
        if (firstInvalidStep >= 0) {
            this.goTo(firstInvalidStep);
            this.$nextTick(() => invalidByStep[firstInvalidStep].focus());
            return;
        }

        this.submitting = true;
        this.$refs.form.submit();
    },

    toggleSecondMatricula(checked) {
        this.hasSecondMatricula = checked;
        if (checked) return;
        this.$refs.form.elements.second_matricula.value = '';
        this.clear('second_matricula');
    },

    maskCpf(event) {
        event.target.value = formatCpf(event.target.value);
    },

    maskRg(event) {
        event.target.value = sanitizeRg(event.target.value);
    },
}));

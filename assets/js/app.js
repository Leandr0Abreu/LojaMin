"use strict";
/* ==========================================================================
   Loja Minimalista — interatividade do front-end
   Fonte: app.ts  →  compilado para app.js  (veja README)
   Nada aqui altera o back-end: apenas melhora a experiência no navegador.
   ========================================================================== */
const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
function toast(message, tone = "ok") {
    const host = $("[data-toasts]");
    if (!host)
        return;
    const el = document.createElement("div");
    el.className = "toast" + (tone === "err" ? " is-err" : "");
    el.textContent = message;
    host.appendChild(el);
    window.setTimeout(() => {
        el.classList.add("is-leaving");
        el.addEventListener("animationend", () => el.remove(), { once: true });
    }, 2800);
}
/* ---------- Estado de carregamento de botões ---------- */
function setLoading(button, loading, text = "Aguarde…") {
    var _a;
    if (!button)
        return;
    if (loading) {
        button.setAttribute("aria-busy", "true");
        button.textContent = text;
    }
    else {
        button.removeAttribute("aria-busy");
        button.textContent = (_a = button.dataset.label) !== null && _a !== void 0 ? _a : button.textContent;
    }
}
/* Ao voltar pelo histórico (bfcache), restaura botões travados */
window.addEventListener("pageshow", () => {
    $$("button[aria-busy='true']").forEach((b) => setLoading(b, false));
});
/* ---------- Menu mobile ---------- */
function initMenu() {
    const btn = $("[data-menu-btn]");
    const menu = $("[data-menu]");
    if (!btn || !menu)
        return;
    btn.addEventListener("click", () => {
        const willOpen = menu.classList.contains("hidden");
        menu.classList.toggle("hidden", !willOpen);
        btn.setAttribute("aria-expanded", String(willOpen));
        btn.setAttribute("aria-label", willOpen ? "Fechar menu" : "Abrir menu");
    });
}
/* ---------- Stepper de quantidade (respeita o estoque via atributo max) ---------- */
function initSteppers() {
    $$("[data-qty]").forEach((wrap) => {
        const input = $("input", wrap);
        const minus = $("[data-qty-minus]", wrap);
        const plus = $("[data-qty-plus]", wrap);
        if (!input || !minus || !plus)
            return;
        const min = Number(input.min) || 1;
        const max = Number(input.max) || Number.POSITIVE_INFINITY;
        const sync = () => {
            let value = parseInt(input.value, 10);
            if (Number.isNaN(value))
                value = min;
            if (value > max)
                toast(`Máximo em estoque: ${max}`, "err");
            value = Math.min(Math.max(value, min), max);
            input.value = String(value);
            minus.disabled = value <= min;
            plus.disabled = value >= max;
            plus.title = value >= max ? `Máximo em estoque: ${max}` : "";
        };
        minus.addEventListener("click", () => {
            input.value = String((parseInt(input.value, 10) || min) - 1);
            sync();
        });
        plus.addEventListener("click", () => {
            input.value = String((parseInt(input.value, 10) || min) + 1);
            sync();
        });
        input.addEventListener("change", sync);
        input.addEventListener("blur", sync);
        sync();
    });
}
/* ---------- Adicionar ao carrinho: feedback no botão ---------- */
function initAddForms() {
    $$("[data-add-form]").forEach((form) => {
        form.addEventListener("submit", () => {
            const input = $("input[name='quantidade']", form);
            if (input) {
                const min = Number(input.min) || 1;
                const max = Number(input.max) || Number.POSITIVE_INFINITY;
                const value = parseInt(input.value, 10);
                input.value = String(Math.min(Math.max(Number.isNaN(value) ? min : value, min), max));
            }
            setLoading($("button[type='submit']", form), true, "Adicionando…");
        });
    });
}
/* ---------- Vitrine: busca + ordenação (apenas no navegador) ---------- */
function initCatalog() {
    const grid = $("[data-grid]");
    if (!grid)
        return;
    const cards = $$("[data-card]", grid);
    const search = $("[data-search]");
    const sort = $("[data-sort]");
    const counter = $("[data-count]");
    const counterLabel = $("[data-count-label]");
    const emptyState = $("[data-empty-search]");
    const normalize = (s) => s.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
    const price = (el) => { var _a; return Number((_a = el.dataset.price) !== null && _a !== void 0 ? _a : 0); };
    const name = (el) => { var _a; return (_a = el.dataset.name) !== null && _a !== void 0 ? _a : ""; };
    const apply = () => {
        var _a;
        const query = normalize((_a = search === null || search === void 0 ? void 0 : search.value) !== null && _a !== void 0 ? _a : "");
        const ordered = cards.slice();
        switch (sort === null || sort === void 0 ? void 0 : sort.value) {
            case "preco-asc":
                ordered.sort((a, b) => price(a) - price(b));
                break;
            case "preco-desc":
                ordered.sort((a, b) => price(b) - price(a));
                break;
            case "nome":
                ordered.sort((a, b) => name(a).localeCompare(name(b), "pt-BR"));
                break;
        }
        let visible = 0;
        ordered.forEach((card) => {
            grid.appendChild(card);
            const match = normalize(name(card)).includes(query);
            card.classList.toggle("hidden", !match);
            if (match)
                visible++;
        });
        if (counter)
            counter.textContent = String(visible);
        if (counterLabel)
            counterLabel.textContent = visible === 1 ? "peça" : "peças";
        emptyState === null || emptyState === void 0 ? void 0 : emptyState.classList.toggle("hidden", visible !== 0);
        grid.classList.toggle("hidden", visible === 0);
    };
    search === null || search === void 0 ? void 0 : search.addEventListener("input", apply);
    sort === null || sort === void 0 ? void 0 : sort.addEventListener("change", apply);
}
/* ---------- Validação dos formulários de login/cadastro ---------- */
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
function validateField(input, form) {
    const value = input.value;
    switch (input.dataset.rule) {
        case "nome":
            return value.trim().length < 2 ? "Informe seu nome." : "";
        case "email":
            if (!value.trim())
                return "Informe seu e-mail.";
            return EMAIL_RE.test(value.trim()) ? "" : "E-mail inválido.";
        case "obrigatorio":
            return value ? "" : "Informe sua senha.";
        case "senha":
            if (!value)
                return "Crie uma senha.";
            return value.length < 6 ? "A senha precisa ter ao menos 6 caracteres." : "";
        case "confirmar": {
            const main = $("[data-password-main]", form);
            if (!value)
                return "Repita a senha.";
            return main && main.value !== value ? "As senhas não coincidem." : "";
        }
        default:
            return "";
    }
}
function showFieldError(input, message) {
    const field = input.closest("[data-field]");
    const out = field ? $("[data-error]", field) : null;
    input.setAttribute("aria-invalid", message ? "true" : "false");
    if (!out)
        return;
    out.textContent = message;
    out.classList.toggle("hidden", !message);
}
function initValidation() {
    $$("form[data-validate]").forEach((form) => {
        const inputs = $$("input[data-rule]", form);
        inputs.forEach((input) => {
            input.addEventListener("blur", () => {
                if (input.value !== "" || input.getAttribute("aria-invalid") === "true") {
                    showFieldError(input, validateField(input, form));
                }
            });
            input.addEventListener("input", () => {
                if (input.getAttribute("aria-invalid") === "true") {
                    showFieldError(input, validateField(input, form));
                }
            });
        });
        form.addEventListener("submit", (event) => {
            const invalid = inputs.filter((input) => {
                const message = validateField(input, form);
                showFieldError(input, message);
                return message !== "";
            });
            if (invalid.length > 0) {
                event.preventDefault();
                invalid[0].focus();
                return;
            }
            setLoading($("button[type='submit']", form), true, "Enviando…");
        });
    });
}
/* ---------- Mostrar / ocultar senha ---------- */
function initPasswordToggle() {
    $$("[data-toggle-password]").forEach((btn) => {
        const input = btn.parentElement ? $("input", btn.parentElement) : null;
        if (!input)
            return;
        btn.addEventListener("click", () => {
            const show = input.type === "password";
            input.type = show ? "text" : "password";
            btn.textContent = show ? "Ocultar" : "Mostrar";
            btn.setAttribute("aria-label", show ? "Ocultar senha" : "Mostrar senha");
        });
    });
}
/* ---------- Medidor de força da senha (cadastro) ---------- */
function initStrengthMeter() {
    const input = $("[data-password-main]");
    const meter = $("[data-strength]");
    const label = $("[data-strength-label]");
    if (!input || !meter || !label)
        return;
    const labels = ["Use letras, números e símbolos.", "Fraca", "Razoável", "Boa", "Forte"];
    input.addEventListener("input", () => {
        const v = input.value;
        let score = 0;
        if (v.length >= 8)
            score++;
        if (/[a-z]/.test(v) && /[A-Z]/.test(v))
            score++;
        if (/\d/.test(v))
            score++;
        if (/[^A-Za-z0-9]/.test(v))
            score++;
        if (v.length === 0)
            score = 0;
        else if (score === 0)
            score = 1;
        meter.dataset.level = String(score);
        label.textContent = labels[score];
    });
    /* Revalida a confirmação se a senha principal mudar */
    input.addEventListener("input", () => {
        const form = input.closest("form");
        const confirm = form ? $("[data-rule='confirmar']", form) : null;
        if (confirm && confirm.value)
            showFieldError(confirm, validateField(confirm, form));
    });
}
/* ---------- Carrinho: modal de finalização ---------- */
function initCheckout() {
    var _a;
    const modal = $("[data-modal]");
    const openBtn = $("[data-checkout-open]");
    if (!modal || !openBtn)
        return;
    const step = (name) => {
        $$("[data-modal-step]", modal).forEach((el) => {
            el.classList.toggle("hidden", el.dataset.modalStep !== name);
        });
    };
    openBtn.addEventListener("click", () => {
        step("confirmar");
        modal.showModal();
    });
    $$("[data-modal-close]", modal).forEach((b) => b.addEventListener("click", () => modal.close()));
    /* Clique no fundo fecha o modal */
    modal.addEventListener("click", (event) => {
        if (event.target === modal)
            modal.close();
    });
    (_a = $("[data-checkout-confirm]", modal)) === null || _a === void 0 ? void 0 : _a.addEventListener("click", () => step("sucesso"));
}
/* ---------- Inicialização ---------- */
document.addEventListener("DOMContentLoaded", () => {
    initMenu();
    initSteppers();
    initAddForms();
    initCatalog();
    initValidation();
    initPasswordToggle();
    initStrengthMeter();
    initCheckout();
});

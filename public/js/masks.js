/**
 * Máscaras de input para formatos brasileiros.
 *
 * Uso: adicione data-mask no input.
 *   <input type="tel"  data-mask="phone">
 *   <input type="text" data-mask="cpf">
 *   <input type="text" data-mask="cnpj">
 *   <input type="text" data-mask="cpf-cnpj">  (alterna conforme o tamanho)
 *   <input type="text" data-mask="cep">
 *
 * O valor enviado ao servidor permanece formatado (com pontos/traços).
 * Se quiser apenas dígitos no backend, use data-mask-numeric-only="true"
 * e um input hidden será populado com o valor sem máscara.
 */
(function () {
    'use strict';

    const onlyDigits = (v) => (v || '').replace(/\D/g, '');

    const formatters = {
        phone(v) {
            const d = onlyDigits(v).slice(0, 11);
            if (d.length <= 2)  return d.length ? `(${d}` : '';
            if (d.length <= 6)  return `(${d.slice(0, 2)}) ${d.slice(2)}`;
            if (d.length <= 10) return `(${d.slice(0, 2)}) ${d.slice(2, 6)}-${d.slice(6)}`;
            return `(${d.slice(0, 2)}) ${d.slice(2, 7)}-${d.slice(7)}`;
        },

        cpf(v) {
            const d = onlyDigits(v).slice(0, 11);
            if (d.length <= 3)  return d;
            if (d.length <= 6)  return `${d.slice(0, 3)}.${d.slice(3)}`;
            if (d.length <= 9)  return `${d.slice(0, 3)}.${d.slice(3, 6)}.${d.slice(6)}`;
            return `${d.slice(0, 3)}.${d.slice(3, 6)}.${d.slice(6, 9)}-${d.slice(9)}`;
        },

        cnpj(v) {
            const d = onlyDigits(v).slice(0, 14);
            if (d.length <= 2)  return d;
            if (d.length <= 5)  return `${d.slice(0, 2)}.${d.slice(2)}`;
            if (d.length <= 8)  return `${d.slice(0, 2)}.${d.slice(2, 5)}.${d.slice(5)}`;
            if (d.length <= 12) return `${d.slice(0, 2)}.${d.slice(2, 5)}.${d.slice(5, 8)}/${d.slice(8)}`;
            return `${d.slice(0, 2)}.${d.slice(2, 5)}.${d.slice(5, 8)}/${d.slice(8, 12)}-${d.slice(12)}`;
        },

        'cpf-cnpj'(v) {
            const d = onlyDigits(v);
            return d.length <= 11 ? formatters.cpf(v) : formatters.cnpj(v);
        },

        cep(v) {
            const d = onlyDigits(v).slice(0, 8);
            if (d.length <= 5) return d;
            return `${d.slice(0, 5)}-${d.slice(5)}`;
        },
    };

    function applyMask(input) {
        const type = input.dataset.mask;
        const formatter = formatters[type];
        if (!formatter) return;

        // Aplica máscara no valor inicial (vindo do server, ex: old())
        if (input.value) {
            input.value = formatter(input.value);
        }

        input.addEventListener('input', (e) => {
            const start = e.target.selectionStart;
            const oldLength = e.target.value.length;
            e.target.value = formatter(e.target.value);
            const newLength = e.target.value.length;
            // Mantém o cursor numa posição razoável após reformatação
            const newPos = Math.max(0, start + (newLength - oldLength));
            e.target.setSelectionRange(newPos, newPos);
        });

        // Cola: limpa formatação inesperada
        input.addEventListener('paste', (e) => {
            setTimeout(() => {
                e.target.value = formatter(e.target.value);
            }, 0);
        });
    }

    function init() {
        document.querySelectorAll('input[data-mask]').forEach(applyMask);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expõe para reaplicar em conteúdo dinâmico (Livewire, modais, etc.)
    window.entregoMasks = { apply: applyMask, init };
})();

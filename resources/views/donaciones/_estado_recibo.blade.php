<style>
[data-recibo-condicional][hidden] { display: none !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selector = document.querySelector('select[name="recibo_empresa"]');
    if (!selector) return;
    const formulario = selector.closest('form');
    const bloques = formulario.querySelectorAll('[data-recibo-condicional]');
    function actualizarRecibo() {
        const existe = selector.value === '1';
        bloques.forEach(bloque => {
            bloque.hidden = !existe;
            bloque.querySelectorAll('input, select, textarea, button').forEach(campo => {
                campo.disabled = !existe;
            });
        });
    }
    selector.addEventListener('change', actualizarRecibo);
    actualizarRecibo();
});
</script>

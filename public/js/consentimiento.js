document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('firmaCanvas');
    if (!canvas) return;

    canvas.width  = canvas.parentElement.offsetWidth || 400;
    canvas.height = 45;

    const ctx = canvas.getContext('2d');
    let drawing   = false;
    let firmaSigned = false;

    ctx.strokeStyle = '#212529';
    ctx.lineWidth   = 1.8;
    ctx.lineCap     = 'round';
    ctx.lineJoin    = 'round';

    function getPos(e) {
        const r      = canvas.getBoundingClientRect();
        const scaleX = canvas.width  / r.width;
        const scaleY = canvas.height / r.height;
        const src    = e.touches ? e.touches[0] : e;
        return {
            x: (src.clientX - r.left) * scaleX,
            y: (src.clientY - r.top)  * scaleY
        };
    }

    function stampFecha() {
        if (firmaSigned) return;
        firmaSigned = true;
        // UTC-5 (America/Bogota — no daylight saving)
        const iso = new Date().toLocaleString('sv-SE', { timeZone: 'America/Bogota' });
        document.getElementById('fechaFirmaData').value = iso;
    }

    canvas.addEventListener('mousedown', function (e) {
        drawing = true;
        stampFecha();
        ctx.beginPath();
        const p = getPos(e);
        ctx.moveTo(p.x, p.y);
    });
    canvas.addEventListener('mousemove', function (e) {
        if (!drawing) return;
        const p = getPos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    });
    canvas.addEventListener('mouseup',    function () { drawing = false; });
    canvas.addEventListener('mouseleave', function () { drawing = false; });

    canvas.addEventListener('touchstart', function (e) {
        e.preventDefault();
        drawing = true;
        stampFecha();
        ctx.beginPath();
        const p = getPos(e);
        ctx.moveTo(p.x, p.y);
    }, { passive: false });
    canvas.addEventListener('touchmove', function (e) {
        e.preventDefault();
        if (!drawing) return;
        const p = getPos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    }, { passive: false });
    canvas.addEventListener('touchend', function () { drawing = false; });
    canvas.addEventListener('_resetFirmaSigned', function () { firmaSigned = false; });

    document.getElementById('formAceptar').addEventListener('submit', function () {
        document.getElementById('firmaData').value = canvas.toDataURL('image/png');
    });
});

function mostrarFirma() {
    document.getElementById('botonesConsentimiento').style.display = 'none';
    document.getElementById('seccionFirma').style.display = 'block';
}

function clearFirma() {
    const canvas = document.getElementById('firmaCanvas');
    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById('fechaFirmaData').value = '';
    canvas.dispatchEvent(new Event('_resetFirmaSigned'));
}

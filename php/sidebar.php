

<div class="sidebar">
	<ul class="nav flex-column">
        <li class="nav-section">
			<h4 class="text-section">Menú </h4>
		</li>
		<li class="nav-item">
            <a class="nav-link active" href="/inversiones">Dashboard</a>
        </li>
		<li class="nav-item">
            <a class="nav-link active" href="/inversiones/php/compras.php">Compras</a>
        </li>
		<li class="nav-item">
            <a class="nav-link active" href="/inversiones/php/dividendos.php">Dividendos</a>
        </li>
		<li class="nav-item">
            <a class="nav-link active" href="/inversiones/php/rentabilidad.php">Rentabilidad</a>
        </li>
    </ul>    
</div>

<script>
    document.querySelectorAll('.nav-item > .nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const nextElem = this.nextElementSibling; // Selecciona el siguiente elemento

            // Previene el comportamiento de enlace si hay un submenú
            if (nextElem && nextElem.tagName === 'UL') {
                e.preventDefault(); // Evitar comportamiento predeterminado
                nextElem.classList.toggle('d-block'); // Alternar la clase para mostrar/ocultar
            }
        });
    });
</script>
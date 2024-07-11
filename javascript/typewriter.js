<script>
    document.addEventListener('DOMContentLoaded', function() {
        var containerInfo = document.getElementById('container-info');
        var text = containerInfo.textContent;
        containerInfo.textContent = '';
        var index = 0;

        function typeWriter() {
            if (index < text.length) {
                containerInfo.textContent += text.charAt(index);
                index++;
                setTimeout(typeWriter, 100);
            }
        }

        typeWriter();
    });
</script>

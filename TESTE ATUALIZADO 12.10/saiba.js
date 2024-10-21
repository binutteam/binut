// Função para adicionar efeito de zoom e opacidade nas imagens da equipe
document.addEventListener('DOMContentLoaded', function() {
    const equipeImages = document.querySelectorAll('.equipe-img');

    equipeImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            img.style.transform = 'scale(1.1)'; // Aumenta a imagem
            img.style.opacity = '0.8'; // Diminui a opacidade
        });

        img.addEventListener('mouseleave', function() {
            img.style.transform = 'scale(1)'; // Retorna ao tamanho normal
            img.style.opacity = '1'; // Restaura a opacidade
        });
    });
});

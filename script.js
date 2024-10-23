document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelector('.carousel-images');
    const carouselImages = document.querySelectorAll('.carousel-image');
    let index = 0;
    const totalImages = carouselImages.length;
    const interval = 3000; // Intervalo de 3 segundos entre as trocas

    const updateCarousel = () => {
        const width = carouselImages[0].offsetWidth;
        images.style.transform = `translateX(${-index * width}px)`;
    };

    const nextImage = () => {
        index = (index + 1) % totalImages;
        updateCarousel();
    };

    setInterval(nextImage, interval);
});
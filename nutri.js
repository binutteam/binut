document.querySelectorAll(".agenda button").forEach(button => {
    button.addEventListener("click", function() {
        alert(`Você selecionou o horário ${this.textContent}`);
    });
});

// botão de voltar
document.getElementById("backButton").addEventListener("click", function() {
    window.history.back(); // Volta para a página anterior
});

// Abrir e fechar modal de mudar data
var modalData = document.getElementById('modalMudarData');
var btnMudarData = document.getElementById('mudarDataBtn');
var closeData = document.getElementById('closeDataModal');
var confirmarDataBtn = document.getElementById('confirmarDataBtn');

// Abrir e fechar modal de mudar nutricionista
var modalNutri = document.getElementById('modalMudarNutri');
var btnMudarNutri = document.getElementById('mudarNutriBtn');
var closeNutri = document.getElementById('closeNutriModal');
var confirmarNutriBtn = document.getElementById('confirmarNutriBtn');

// Contêineres de confirmação e formulário
var formContainer = document.getElementById('formContainer');
var confirmacaoContainer = document.getElementById('confirmacaoContainer');

// Campos de confirmação
var confirmacaoData = document.getElementById('confirmacaoData');
var confirmacaoHorario = document.getElementById('confirmacaoHorario');
var confirmacaoNutri = document.getElementById('confirmacaoNutri');

// Dados da consulta
var dataConsulta = '';
var horarioConsulta = '';
var nutricionistaConsulta = 'Adriana Moura Araujo Caetano';

// Função para abrir e fechar modais
btnMudarData.onclick = function() {
    modalData.style.display = "flex";
}

closeData.onclick = function() {
    modalData.style.display = "none";
}

btnMudarNutri.onclick = function() {
    modalNutri.style.display = "flex";
}

closeNutri.onclick = function() {
    modalNutri.style.display = "none";
}

// Confirmar nova data e horário
confirmarDataBtn.onclick = function() {
    var novaData = document.getElementById('nova-data').value;
    var novoHorario = document.getElementById('novo-horario').value;
    
    if (novaData && novoHorario) {
        dataConsulta = novaData;
        horarioConsulta = novoHorario;
        alert('Consulta remarcada para ' + novaData + ' às ' + novoHorario);
        modalData.style.display = "none";
        mostrarConfirmacao();
    } else {
        alert('Por favor, selecione uma data e horário.');
    }
}

// Confirmar novo nutricionista
confirmarNutriBtn.onclick = function() {
    var nutricionistaSelecionado = document.getElementById('nutricionista').value;
    
    nutricionistaConsulta = nutricionistaSelecionado;
    alert('Nutricionista alterado para: ' + nutricionistaSelecionado);
    modalNutri.style.display = "none";
    mostrarConfirmacao();
}

// Função para mostrar a tela de confirmação
function mostrarConfirmacao() {
    formContainer.style.display = "none";
    confirmacaoContainer.style.display = "block";

    // Preencher os dados na tela de confirmação
    confirmacaoData.textContent = dataConsulta ? dataConsulta : '11 Out 2024';
    confirmacaoHorario.textContent = horarioConsulta ? horarioConsulta : '15:00';
    confirmacaoNutri.textContent = nutricionistaConsulta;
}

// Função para baixar as informações da consulta
document.getElementById('baixarInfoBtn').onclick = function() {
    var infoConsulta = 
        "Consulta confirmada:\n" +
        "Data: " + confirmacaoData.textContent + "\n" +
        "Horário: " + confirmacaoHorario.textContent + "\n" +
        "Nutricionista: " + confirmacaoNutri.textContent;

    var blob = new Blob([infoConsulta], { type: 'text/plain' });
    var link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = 'confirmacao_consulta.txt';
    link.click();
}

// Fechar modais se clicar fora
window.onclick = function(event) {
    if (event.target == modalData) {
        modalData.style.display = "none";
    } else if (event.target == modalNutri) {
        modalNutri.style.display = "none";
    }
}

document.getElementById("backButton").addEventListener("click", function() {
    window.history.back(); // Volta para a página anterior
});
// Referência aos elementos
const missoesDiv = document.getElementById('missoes');
const avancarDiaBtn = document.getElementById('avancarDiaBtn');

// Função para carregar as missões
function carregarMissoes() {
    axios.get('URL_DO_SEU_BACKEND/aqui')
        .then(response => {
            const missoes = response.data;
            missoesDiv.innerHTML = ''; // Limpar conteúdo anterior

            missoes.forEach(missao => {
                const divMissao = document.createElement('div');
                divMissao.classList.add('missao');
                divMissao.innerHTML = `
                    <h3>${missao.titulo}</h3>
                    <p>${missao.descricao}</p>
                `;
                missoesDiv.appendChild(divMissao);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar missões:', error);
        });
}

// Função para avançar para o próximo dia
function avancarDia() {
    axios.post('URL_DO_SEU_BACKEND/avancarDia')
        .then(response => {
            alert('Dia avançado com sucesso!');
            carregarMissoes(); // Recarregar missões após avançar
        })
        .catch(error => {
            console.error('Erro ao avançar para o próximo dia:', error);
        });
}

// Evento de clique no botão
avancarDiaBtn.addEventListener('click', avancarDia);

// Carregar as missões ao carregar a página
window.onload = carregarMissoes;

import React, { useEffect, useState } from "react";
import axios from "axios";
import "./App.css";


function App() {
  const [missoes, setMissoes] = useState([]);
  const [titulo, setTitulo] = useState("");
  const [descricao, setDescricao] = useState("");

  // Carrega as missões do backend
  useEffect(() => {
    carregarMissoes();
  }, []);

  const carregarMissoes = () => {
    axios
      .get("http://localhost/jogo_da_vida/missoes.php")
      .then((response) => setMissoes(response.data))
      .catch((error) => console.error("Erro ao buscar missões:", error));
  };

  // Função para atualizar o progresso da missão
  const atualizarProgresso = (id, valor) => {
    axios.post("http://localhost/jogo_da_vida/atualizar_progresso.php", { missao_id: id, progresso: valor })
      .then(() => {
        // Atualiza a lista de missões no front-end para refletir a mudança
        setMissoes(prevMissoes =>
          prevMissoes.map(missao =>
            missao.id === id ? { ...missao, progresso_total: missao.progresso_total + valor } : missao
          )
        );
      })
      .catch((error) => console.error("Erro ao atualizar progresso:", error));
  };

  return (
    <div>
      <h1>Lista de Missões</h1>
      <ul>
        {missoes.map((missao) => (
          <li key={missao.id}>
            <strong>{missao.titulo}</strong> - {missao.descricao}
            <div>
              <p>Progresso: {missao.progresso_total} {missao.unidade}</p>
              <button onClick={() => atualizarProgresso(missao.id, 1)}>+</button>
              <button onClick={() => atualizarProgresso(missao.id, -1)}>-</button>
              <button onClick={() => atualizarProgresso(missao.id, 0)}>Concluir</button>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
}


  // Função para adicionar missão
  const adicionarMissao = () => {
    if (!titulo || !descricao) {
      alert("Preencha todos os campos!");
      return;
    }

    axios
      .post("http://localhost/jogo_da_vida/adicionar_missao.php", { titulo, descricao })
      .then((response) => {
        alert(response.data.message);
        carregarMissoes(); // Atualiza a lista após adicionar
        setTitulo("");
        setDescricao("");
      })
      .catch((error) => console.error("Erro ao adicionar missão:", error));
  };

  // Função para remover missão
  const removerMissao = (id) => {
    if (!window.confirm("Tem certeza que deseja excluir esta missão?")) return;

    axios
      .post("http://localhost/jogo_da_vida/remover_missao.php", { id })
      .then((response) => {
        alert(response.data.message);
        carregarMissoes(); // Atualiza a lista após remover
      })
      .catch((error) => console.error("Erro ao remover missão:", error));
  };

  return (
    <div style={{ padding: "20px", fontFamily: "Arial" }}>
      <h1>Lista de Missões</h1>

      {/* Formulário para adicionar nova missão */}
      <div>
        <input
          type="text"
          placeholder="Título da missão"
          value={titulo}
          onChange={(e) => setTitulo(e.target.value)}
        />
        <input
          type="text"
          placeholder="Descrição"
          value={descricao}
          onChange={(e) => setDescricao(e.target.value)}
        />
        <button onClick={adicionarMissao}>Adicionar Missão</button>
      </div>

      {/* Lista de missões */}
      <ul>
        {missoes.length > 0 ? (
          missoes.map((missao) => (
            <li key={missao.id}>
              <strong>{missao.titulo}</strong>: {missao.descricao}
              <button onClick={() => removerMissao(missao.id)} style={{ marginLeft: "10px", color: "White" }}>
                ❌ Excluir
              </button>
            </li>
          ))
        ) : (
          <p>Nenhuma missão encontrada.</p>
        )}
      </ul>
    </div>
  );


export default App;

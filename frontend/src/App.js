import React, { useEffect, useState } from "react";
import axios from "axios";
import "./App.css";

function App() {
  const [missoes, setMissoes] = useState([]);
  const [titulo, setTitulo] = useState("");
  const [descricao, setDescricao] = useState("");
  const [unidade, setUnidade] = useState(""); 
  const [meta, setMeta] = useState(""); 
  const [dificuldade, setDificuldade] = useState(""); 
  const unidades = ["passos", "litros", "km/h", "quantidade"]; 
  const dificuldades = ["Fácil", "Média", "Difícil"]; 
  const xpPorDificuldade = { 
    Fácil: 10,
    Média: 25,
    Difícil: 50
  };
  const progressoPorDificuldade = {
    Fácil: 10,
    Média: 15,
    Difícil: 20
  };

  useEffect(() => {
    carregarMissoes();
  }, []);

  const carregarMissoes = () => {
    axios
      .get("http://localhost/jogo_da_vida/missoes.php")
      .then((response) => setMissoes(response.data))
      .catch((error) => console.error("Erro ao buscar missões:", error));
  };

  const adicionarMissao = () => {
    if (!titulo || !descricao || !unidade || !meta || !dificuldade) {
      alert("Preencha todos os campos!");
      return;
    }

    const xp = xpPorDificuldade[dificuldade];
    const progresso = 0; // Começa com progresso 0

    axios
      .post("http://localhost/jogo_da_vida/adicionar_missao.php", {
        titulo,
        descricao,
        unidade,
        meta,
        dificuldade,
        xp,
        progresso, // Começa com 0
      })
      .then((response) => {
        alert(response.data.message);
        carregarMissoes();
        setTitulo("");
        setDescricao("");
        setUnidade("");
        setMeta("");
        setDificuldade("");
      })
      .catch((error) => console.error("Erro ao adicionar missão:", error));
  };

  const removerMissao = (id) => {
    if (!window.confirm("Tem certeza que deseja excluir esta missão?")) return;

    axios
      .post("http://localhost/jogo_da_vida/remover_missao.php", { id })
      .then((response) => {
        alert(response.data.message);
        carregarMissoes();
      })
      .catch((error) => console.error("Erro ao remover missão:", error));
  };

  const concluirMissao = (id, xp) => {
    axios
      .post("http://localhost/jogo_da_vida/concluir_missao.php", { player_id: 1, missao_id: id, xp: xp })
      .then((response) => {
        alert(response.data.message);
        setMissoes((prevMissoes) =>
          prevMissoes.map((missao) => (missao.id === id ? { ...missao, progresso_total: 100 } : missao))
        );
      })
      .catch((error) => {
        console.error("Erro ao concluir missão:", error);
      });
  };

  const atualizarProgresso = (id, progresso) => {
    // Atualiza o progresso com o valor correto de acordo com a dificuldade
    axios
      .post("http://localhost/jogo_da_vida/atualizar_progresso.php", { missao_id: id, progresso })
      .then(() => {
        setMissoes((prevMissoes) =>
          prevMissoes.map((missao) =>
            missao.id === id
              ? {
                  ...missao,
                  progresso_total: Math.min(missao.progresso_total + progresso, 100), // Limita o progresso a 100
                }
              : missao
          )
        );
      })
      .catch((error) => console.error("Erro ao atualizar progresso:", error));
  };

  return (
    <div style={{ padding: "20px", fontFamily: "Arial" }}>
      <h1>Lista de Missões</h1>

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
        <select value={unidade} onChange={(e) => setUnidade(e.target.value)}>
          <option value="">Selecione a unidade</option>
          {unidades.map((item, index) => (
            <option key={index} value={item}>
              {item}
            </option>
          ))}
        </select>
        <input
          type="number"
          placeholder="Meta"
          value={meta}
          onChange={(e) => setMeta(e.target.value)}
        />
        <select value={dificuldade} onChange={(e) => setDificuldade(e.target.value)}>
          <option value="">Selecione a dificuldade</option>
          {dificuldades.map((item, index) => (
            <option key={index} value={item}>
              {item}
            </option>
          ))}
        </select>
        <button onClick={adicionarMissao}>Adicionar Missão</button>
      </div>

      <ul>
        {missoes.length > 0 ? (
          missoes.map((missao) => (
            <li key={missao.id}>
              <strong>{missao.titulo}</strong>: {missao.descricao}
              <div>
                <p>Progresso: {missao.progresso_total} {missao.unidade}</p>
                <progress value={missao.progresso_total} max="100"></progress>
                <button onClick={() => atualizarProgresso(missao.id, progressoPorDificuldade[missao.dificuldade])}>
                  Progredir
                </button>
                <button onClick={() => concluirMissao(missao.id, xpPorDificuldade[missao.dificuldade])}>
                  Concluir Missão
                </button>
                <button onClick={() => removerMissao(missao.id)} style={{ marginLeft: "10px", color: "white" }}>
                  ❌ Excluir
                </button>
              </div>
            </li>
          ))
        ) : (
          <p>Nenhuma missão encontrada.</p>
        )}
      </ul>
    </div>
  );
}

export default App;

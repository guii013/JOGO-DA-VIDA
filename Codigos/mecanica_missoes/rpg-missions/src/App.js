import logo from './logo.svg';
import './App.css';

import React, { useEffect, useState } from "react";
import axios from "axios";

function App() {
  const [data, setData] = useState("");

  useEffect(() => {
    axios.get("http://localhost/jogo_da_vida/test-db.php")
      .then((response) => setData(response.data))
      .catch((error) => setData("Erro ao conectar ao backend"));
  }, []);

  return <div><h1>Conexão: {data}</h1></div>;
}

export default App;

export default App;

import React, { useEffect, useState } from "react";
import axios from "axios";

function App() {
  const [data, setData] = useState("http://localhost/jogo_da_vida/test-db.php");

  useEffect(() => {
    axios
      .get("http://localhost/jogo_da_vida/test-db.php")
      .then((response) => setData(response.data))
      .catch((error) => setData("erro!!"));
  }, []);

  return (
    <div>
      <h1>Conexão: {data}</h1>
    </div>
  );
}

export default App;
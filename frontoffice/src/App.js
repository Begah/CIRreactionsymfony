import React from "react";
import './App.css';

import logo from './logo.svg';

import Champ from './components/Champ.js';
import Graphique from "./components/Graphique";

function App() {
  const [data, setData] = React.useState({ debut: 1000, fin: 2018, espece: "Baleine de Cuvier" })

  function form_submit(debut, fin, espece) {
      setData({ debut:debut, fin:fin, espece:espece });
  }
  
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
      </header>
        
      <div className='corps'>
        <Champ submit_callback={form_submit}/>
        <Graphique info={data}/>
      </div>
      <footer id="footer">
        <p>
          test footer
        </p>
      </footer>
    </div>
  );
}


export default App;

import logo from './logo.svg';
import Champ from './components/Champ.js';
import './App.css';


//faire un if si y a les infos dans la variable 
function App() {
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
      </header>
        
      <div className='corps'>
        <Champ/>
      </div>
    </div>
  );
}

/* Pour quand ca fera plus de la merde
  <Champ />
  {Graphique && <Graphique data={tableau}}/>

  ou

  if(setEnvoie == false){
    <Champ/>
  }
  else{
    <Graphique/>
  }
*/

export default App;

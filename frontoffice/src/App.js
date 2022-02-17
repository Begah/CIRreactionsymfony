import logo from './logo.svg';
import Champ from './components/Champ.js';
import './App.css';

 
function App() {

  

  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
      </header>
        
      <div className='corps'>
        <Champ/>
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

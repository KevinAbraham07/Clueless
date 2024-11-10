import logo from './logo.svg';
import './App.css';
import './styles.css';
import Header from './components/Header';
import Footer from './components/Footer';
function App() {
  return (
    <div className="App">
      <div className='container'>
        <Header></Header>
      </div>
      <header className="App-header">
        <h1>Welcome to MovieDux</h1>
      </header>
      <Footer></Footer>
    </div>
    
  );
}

export default App;

import React from "react";
import Graphique from "./Graphique.js";
import '../App.css';


function Champ(props) {

    const [demande, setDemande] = React.useState({debut : 1997, fin : 2018, espece : "Baleine de Cuvier"}) //mettre une val à chacun
    const [envoie, setEnvoie] = React.useState(false)
    const [data, setData] = React.useState([]) //pour stocker les infos qui sont renvoyé après avoir submit le formulaire = les résultats



    React.useEffect(() => {
        fetch(`https://localhost:8000/api/espece/${demande.debut}/${demande.fin}/${demande.espece}`) //$ permet de dire que c'est la variable
        .then((res) => res.json())
        .then((res) => {console.log(res); setData(res)}) //ca va print les resultats sur la console  -- dans le setData je stock les résultats
    }, [envoie]);



    const [especes, setEspeces] = React.useState([]) //parce que je vais avoir besoin d'espece pour le menu deroulant
    React.useEffect(() => {
        fetch(`https://127.0.0.1:8000/api/espece/_`) //faire attention aux accent pas de guillement
        .then((res) => res.json())
        .then((res) => setEspeces(res)) 
    }, []); //jamais oublié les [] sinon requete toutes les secondes mon fucking server crach comme une pute



    //fonction pour permettre d'avoir une mise à jour des infos envoyer a chaque submit
    const Update = (value) => {
        setDemande({
            ...demande,
            [value.target.name]: value.target.value.trim (), //trim enlève les caracteres problématique
        })
    }



    //fonction d'affichage du graph si on appuie sur le submit
    function affichage() {
        if(data != null){ //verification que les datas sont bien prises en comptent sinon il n'y a rien à afficher
            return(
                <Graphique/>
            )
        }
        else{
            return(
                <p> impossible d'afficher le graph</p>
            )
        }
    }


    //condition ? oui : non; => operateur terner
    //le espece.map va créer un tableau des espece et va les afficher dans les options du select
    return (
        <div>
            <div className="div1">
                <form>
                    <input type="number" defaultValue={1990} min="1990" max="2018" id="DateDeb" name="debut" onChange={(e) => Update(e)}></input> 
                <br />
                    <input type="number" defaultValue={2018} min="1990" max="2018" id="DateFin" name="fin" onChange={(e) => Update(e)}></input>
                <br />

                <select name="espece" id="Nomespece" onChange={(e) => Update(e)}> 
                    {especes.length > 0 ? 
                    especes.map((element) => (
                        <option key={element} value={element}>
                            {element}
                        </option>)) 
                    : 
                        <option value="ok google">
                            Baleine de Cuvier
                        </option>
                    }
                </select>

                <br />
                    <input type="button" value="Envoyer" onClick={() => {setEnvoie(!envoie); console.log("ca marche")}}/>
                <br /> 
            </form>
            </div>
            <div className="div2">
                {affichage()}
            </div>
        </div>
    )
    // onChange={(e) => Update(e)}  ca va faire en sorte d'appeler un script lorsque je vais modifier les valeurs du formulaire  e est l'event
    // renvoie à Update() => va utiliser la requete setDemande afin de l'utiliser avec les valeurs qui ont changer dans le formulaire
}

export default Champ;
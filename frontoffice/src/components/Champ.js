import React from "react";
import '../App.css';


function Champ(props) {
    const [demande, setDemande] = React.useState({ debut: 1997, fin: 2018, espece: "Baleine de Cuvier" }) //mettre une val à chacun
    const [data, setData] = React.useState([]) //pour stocker les infos qui sont renvoyé après avoir submit le formulaire = les résultats

    const [especes, setEspeces] = React.useState([]) //parce que je vais avoir besoin d'espece pour le menu deroulant
    React.useEffect(() => {
        fetch(`http://127.0.0.1:8000/api/espece/_`) //faire attention aux accent pas de guillement
            .then((res) => res.json())
            .then((res) => setEspeces(res))
    }, []); //jamais oublié les [] sinon requete toutes les secondes mon fucking server crach comme une pute



    //fonction pour permettre d'avoir une mise à jour des infos envoyer a chaque submit
    const Update = (value) => {
        setDemande({
            ...demande,
            [value.target.name]: value.target.value.trim(), //trim enlève les caracteres problématique
        })
    }

    const handleSubmit = (event) => {
        event.preventDefault();
        props.submit_callback(demande['debut'], demande['fin'], demande['espece']);
    }

    //condition ? oui : non; => operateur terner
    //le espece.map va créer un tableau des espece et va les afficher dans les options du select
    return (
        <div className="formulaire">
            <form onSubmit={handleSubmit}>
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
                            No connection
                        </option>
                    }
                </select>

                <br />
                <input type="submit" />
                <br />
            </form>
        </div>
    )
    // onChange={(e) => Update(e)}  ca va faire en sorte d'appeler un script lorsque je vais modifier les valeurs du formulaire  e est l'event
    // renvoie à Update() => va utiliser la requete setDemande afin de l'utiliser avec les valeurs qui ont changer dans le formulaire
}

export default Champ;
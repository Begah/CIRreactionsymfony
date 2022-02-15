import React from "react";


function Champ() {
    const [demande, setDemande] = React.useState({debut : 1997, fin : 2020, espece : 0}) //mettre une val à chacun
    const [envoie, setEnvoie] = React.useState(false)

    React.useEffect(() => {
        fetch('http://localhost:8000/api/espece/${demande.debut}/${demande.fin}/${demande.espece}') //$ permet de dire que c'est la variable
        .then((res) => res.json())
        .then((res) => console.log(res)) //ca va print les resultats sur la console
    }, [envoie]);


    const [especes, setEspeces] = React.useState([]) //parce que je vais avoir besoin d'espece pour le menu deroulant
    React.useEffect(() => {
        fetch('http://127.0.0.1:8000/api/espece/${demande.espece}')
        .then((res) => res.json())
        .then((res) => setEspeces(res)) 
    })

    
    const submit = () => {
        setEnvoie(true);
    }

    //API avec id de l'espece et le nom de l'espece
    return (
        <form method="post" action="controller.php" autocomplete="off">
            <input type="number" defaultValue={1990} min="1990" max="2018"></input>
            <br />
            <input type="number" defaultValue={2018} min="1990" max="2018"></input>
            <br />
            <select name="form" id="formesp">
                {especes.map(() => (<option> </option>))}
            </select>
            <br /> 
            <button className="submit" onclick={submit}> 
                Validez
            </button>
        </form>
    )
}

export default Champ;
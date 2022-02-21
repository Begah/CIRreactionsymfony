import App from "../App";
import React from "react"; //a la place du import React from "react";
import CanvasJSReact from './canvasjs.react';//bibliothèque du site de Canvas pour tester un graph

var CanvasJS = CanvasJSReact.CanvasJS;
var CanvasJSChart = CanvasJSReact.CanvasJSChart;

function Graphique(props) {
  const [data, setData] = React.useState();

  let chart = React.useRef();

  React.useEffect(() => {
    fetch(`http://127.0.0.1:8000/api/espece/${props.info['debut']}/${props.info['fin']}/${props.info['espece']}`) //$ permet de dire que c'est la variable
      .then((res) => res.json())
      .then((res) => {
        setData(
          Object.keys(res).map(function (zone, index) { // for each zones, setup the data and name
            return {
              type: "column",
              name: zone,
              showInLegend: true,
              dataPoints: Object.keys(res[zone]).map(function (annee, index2) { // the api sends a json with [1992 => 2, 1993 => 4, ...], we need in the format [{x:1992, y:2}, {x:1993, y:4}]
                return { x: new Date(parseInt(annee),0,0), y: res[zone][annee] }
              })
            }
          })
        )
      }) //ca va print les resultats sur la console  -- dans le setData je stock les résultats
  }, [props]); // Only fetch api when props changes, ie when App.js changes espece, fin or debut


  let options = {
    animationEnabled: true, // Animation unfortunately only works on the first showing, doesn't animate when update new values
    theme: "light2",
    title: {
      text: "Affichage des echouages"
    },
    width: 800,
    height: 500,
    axisX: {
      title: "Annee"
    },
    axisY: {
      title: "Nombre echouage"
    },
    data: data
  }

  return (
    <div class="Graphique">
      <CanvasJSChart options={options}
        onRef={ref => (chart.current = ref)} />
    </div>
  );
}

export default Graphique;
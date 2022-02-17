import App from "../App";
import React, { Component } from "react"; //a la place du import React from "react";
import CanvasJSReact from './canvasjs.react';//bibliothèque du site de Canvas pour tester un graph

var CanvasJS = CanvasJSReact.CanvasJS;
var CanvasJSChart = CanvasJSReact.CanvasJSChart;


class Graphique extends Component {

    render() {

      const options = {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title:{
          text: "Affichage des echouages"
        },
        data:[{
          type: "column",
          dataPoints: [
            { x: 110, y: 21 },
            { x: 120, y: 49 },
            { x: 130, y: 36 }
          ]
        }]
      }

      return (
        <div className="Graphique">
          <CanvasJSChart options = {options} />
        </div>
      );
    }
  }

  export default Graphique;
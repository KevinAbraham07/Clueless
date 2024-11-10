import React from "react";
import '../styles.css';

export default function Footer(){
    const a=new Date().getFullYear();
    return(
        <div>
            <p>
                {a} Copyrights Reserved
            </p>
        </div>
    );
}
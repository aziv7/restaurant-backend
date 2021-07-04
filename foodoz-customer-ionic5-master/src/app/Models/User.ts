import {CoordonneesAuthentification} from "./CoordonneesAuthentification";

export class User {
    id:bigint;
    nom:string;
    prenom:string;
    date_de_naissance:string;
    email:string;
    numero_de_telephone:string;
    coordonnees_authentification:CoordonneesAuthentification;
}

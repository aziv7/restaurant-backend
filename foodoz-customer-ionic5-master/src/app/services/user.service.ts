import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {environment} from '../../environments/environment';
import {User} from '../Models/User';
import {CoordonneesAuthentification} from "../Models/CoordonneesAuthentification";

@Injectable({
  providedIn: 'root'
})
export class UserService {
public userConnected:User;
  constructor(private http: HttpClient) {
  }
  private env = environment.apiUrl;
  AddUser(user: User) {
    return this.http.post(`${this.env}register?login=${user.coordonnees_authentification.login}&password=${user.coordonnees_authentification.password}&nom=${user.nom}&prenom=${user.prenom}
    &date_de_naissance=${user.date_de_naissance}&numero_de_telephone=${user.numero_de_telephone}&email=${user.email}`,
        user,{ withCredentials: true });
  }

  ModifyUser(id,user:User) {
    let login:string=user.coordonnees_authentification.login.toString();
    let password:string=user.coordonnees_authentification.password.toString();
    return this.http.put(`${this.env}user/${id}?login=${login}&password=${password}&nom=${user.nom}&prenom=${user.prenom}
    &date_de_naissance=${user.date_de_naissance}&numero_de_telephone=${user.numero_de_telephone}&email=${user.email}`,
        user,{ withCredentials: true });
  }
  Login(user: User){
    return this.http.post(`${this.env}login?login=${user.coordonnees_authentification.login}&password=${user.coordonnees_authentification.password}`, {},{ observe: 'response', withCredentials: true });
  }
  connected(){
    return this.http.get(`${this.env}connected`, { withCredentials: true });
  }
  logout()
  { this.userConnected=null;
    return this.http.post(`${this.env}logout`, {}, { withCredentials: true });
  }
  getuserByIdWithcoordonnes(login: string){
    return this.http.get<User>(`${this.env}getuser/id/${login}`, { withCredentials: true });
  }
}

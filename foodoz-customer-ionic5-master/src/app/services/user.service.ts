import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {environment} from '../../environments/environment';
import {User} from '../Models/User';

@Injectable({
  providedIn: 'root'
})
export class UserService {
public userConnected:User;
  constructor(private http: HttpClient) {
  }
  private env = environment.apiUrl;
  AddUser(user: User) {
    return this.http.post(`${this.env}register`, user,{ withCredentials: true });
  }
  Login(user: User){
    return this.http.post(`${this.env}login?login=${user.login}&password=${user.password}`, {},{ observe: 'response', withCredentials: true });
  }
  connected(){
    return this.http.get(`${this.env}connected`, { withCredentials: true });
  }
  logout()
  {
    return this.http.post(`${this.env}logout`, {}, { withCredentials: true });
  }
  getuserBylogin(login: string){
    return this.http.get(`${this.env}getuser/login/${login}`, { withCredentials: true });
  }
}

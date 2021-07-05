import { Injectable } from '@angular/core';
import {Subject} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PlatService {
  public plat;
  private plats = [];
   private api = environment.apiUrl;
  updatedPlats = new Subject<Object[]>();
  
  constructor(private http: HttpClient) { }

  getPlats(){
    return this.plats.slice();
  }

  fetchPlats(){
    this.http.get<Object[]>(`${this.api}nos_plats`).subscribe(data=>{this.plats=data
      console.log(this.plats)
      this.updatedPlats.next(this.getPlats());
    })
  }

  providePlat(p){
    let plat;
    if(p!=null)
    this.plats.map(pl=>{if (pl .id===p.id) this.plat=pl})
    else
    this.plat=null
  }

}

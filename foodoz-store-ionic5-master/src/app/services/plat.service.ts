import { Injectable } from '@angular/core';
import {Subject} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment';
@Injectable({
  providedIn: 'root'
})
export class PlatService {
  private plats = [];
   private api = environment.apiUrl;
  updatedPlats = new Subject<Object[]>();
  
  constructor(private http: HttpClient) { }

  getPlats(){
    return this.plats.slice();
  }

  fetchPlats(){
    this.http.get<Object[]>(`${this.api}plat`).subscribe(data=>{this.plats=data
      this.updatedPlats.next(this.getPlats());
    })
  }

}

import { Injectable } from '@angular/core';
import {Subject} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment';
@Injectable({
  providedIn: 'root'
})
export class CategorieService {
  private categories = [];
  private api = environment.apiUrl;
 updatedCategories = new Subject<Object[]>();
 
 constructor(private http: HttpClient) { }
 getCategories(){
  return this.categories.slice();
}

fetchCategories(){
  this.http.get<Object[]>(`${this.api}categorie`).subscribe(data=>{this.categories=data
    this.updatedCategories.next(this.getCategories());
  })
}
}

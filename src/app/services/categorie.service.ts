import { Injectable } from '@angular/core';
import {Subject} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment';
import{Categorie} from '../../models/categorie.model'
@Injectable({
  providedIn: 'root'
})
export class CategorieService {
  private categories:Categorie[] = [];
  private api = environment.apiUrl;
   selected=0
 updatedCategories = new Subject<Object[]>();
 
 constructor(private http: HttpClient) { }
 getCategories(){
  return this.categories.slice();
}
setSelected(value:number){
  this.selected=value
}

fetchCategories(){
  this.http.get<Object[]>(`${this.api}categorie`).subscribe((data:Categorie[])=>{this.categories=data
    
    this.updatedCategories.next(this.getCategories());
  })
}
}

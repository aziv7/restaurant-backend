import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class PlatsService {

  constructor(private Http:HttpClient ) { }
 public postPlat(prix,name, description){
return this.Http.post(`http://localhost:8000/api/plat?nom=${name}&prix=${prix}&description=${description}&image-name=img&image-src=img`,description);
  }
}

import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class StripeService {

  constructor(private http:HttpClient) {

  }


  SendInformationTopay(token){
this.http.post(token,{},{})
  }
}

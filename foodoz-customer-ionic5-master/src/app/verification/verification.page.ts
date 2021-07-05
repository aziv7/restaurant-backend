import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {CookieService} from "ngx-cookie-service";

@Component({
  selector: 'app-verification',
  templateUrl: './verification.page.html',
  styleUrls: ['./verification.page.scss'],
})
export class VerificationPage implements OnInit {

  constructor( private route: Router,private cookieService:CookieService) { }

  ngOnInit() {

  }

set_location() {
    this.route.navigate(['./tabs']);
  } 
}
 
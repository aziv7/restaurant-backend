import { Component } from '@angular/core';
import {CookieService} from 'ngx-cookie-service';
import {UserService} from '../services/user.service';

@Component({
  selector: 'app-tabs',
  templateUrl: 'tabs.page.html',
  styleUrls: ['tabs.page.scss']
})
export class TabsPage {

  constructor(private Userservice:UserService,private cookieService:CookieService,) {

  }

  check() {
    console.log('hi');
    this.Userservice.getuserBylogin(this.cookieService.get('login')).subscribe((data)=> console.log(data));

  }
}

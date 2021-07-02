import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {User} from '../Models/User';
import {UserService} from '../services/user.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage implements OnInit {
user:User;
  constructor(private route: Router, private userservice:UserService) {
this.user=new User();

  }

  ngOnInit() {

  }

verification() {
    this.route.navigate(['./verification']);
    console.log(this.user);
    this.userservice.AddUser(this.user).subscribe(
        (data)=>console.log(data)
    )
  } 
}

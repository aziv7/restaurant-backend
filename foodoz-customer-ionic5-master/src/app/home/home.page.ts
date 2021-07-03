import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.scss'],
})
export class HomePage implements OnInit {
location: string = "home"; 
  constructor(private route: Router) { }

  ngOnInit() {
  }

offers() {
    this.route.navigate(['./offers']);
  } 
stores() {
    this.route.navigate(['./stores']);
  }    
items() {
    this.route.navigate(['./items']);
  }
}

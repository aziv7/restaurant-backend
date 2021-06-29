import {Component, OnInit} from '@angular/core';
import Echo from 'laravel-echo';
import {PlatsService} from "./services/plats.service";
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  title = 'test-ang';
  prix: any;
  name: any;
  description: any;
  plat:any[]=[];
constructor(private platserv:PlatsService) {
}
  ngOnInit(): void {
    console.log('implement echo');
    this.websockets()
  }
  websockets(){
    const echo=new Echo({
      broadcaster: 'pusher',cluster:'mt1',
      key: 'd3d59011cf4df6ed5aa6',
      wsHost: window.location.hostname,
      wsPort: 6001,
      forceTLS: false,
      enabledTransports:['ws']
    });
    echo.channel('channel-message').listen('Test',(resp)=>
    {console.log(resp);this.plat.push(resp.plat);console.log(this.plat)})
  }

  add(prix: any, name: any, description: any) {
    this.platserv.postPlat(prix,name,description).subscribe(
    );
  }
}

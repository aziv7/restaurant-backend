import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { AddMoneyOptionPage } from './add-money-option.page';

describe('AddMoneyOptionPage', () => {
  let component: AddMoneyOptionPage;
  let fixture: ComponentFixture<AddMoneyOptionPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddMoneyOptionPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(AddMoneyOptionPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { PackageTypePage } from './package-type.page';

describe('PackageTypePage', () => {
  let component: PackageTypePage;
  let fixture: ComponentFixture<PackageTypePage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PackageTypePage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(PackageTypePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

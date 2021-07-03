export class Categorie {
  private  id:number;
    nom:string
  private  created_at:Date;
  private  updated_at:Date;
  private  deleted_at:Date;
  public  get _id():number {
      return this.id;
  }
  public plats:Object[];
}

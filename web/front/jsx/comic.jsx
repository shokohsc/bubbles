var Comic = React.createClass({
  render: function() {
    return (
        <div className="comic-item-list">
            <a href={Routing.generate('comic', { id: this.props.comic.id.comicId })} title={this.props.comic.title}>
                <img
                  id={this.props.comic.id.comicId}
                  className="img-thumbnail"
                  src={this.props.comic.thumbnail.path + '/portrait_incredible.' + this.props.comic.thumbnail.extension}
                  alt={this.props.comic.title}
                  height="324"
                  width="216"
                />
            </a>
            <strong>
              <a href={Routing.generate('comic', { id: this.props.comic.id.comicId })} title={this.props.comic.title}>{this.props.comic.title}</a>
            </strong>
        </div>
    );
  }
});

var ComicList = React.createClass({
  render: function() {
    var comicNodes = this.props.data.map(function(comic, index) {
      return (
        <Comic comic={comic} key={comic.id.comicId}>
        <div>
            {comic}
        </div>
        </Comic>
      );
    });
    return (
      <div id="comic-list" className="text-center">
        {comicNodes}
      </div>
    );
  }
});
